<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViteAssetExtension extends AbstractExtension
{
    private ?array $manifestData = null;

    public function __construct(
        private readonly string $env,
        private readonly string $manifest,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_asset', [$this, 'asset'], ['is_safe' => ['html']]),
        ];
    }

    public function asset(string $entry, array $deps): string
    {
        if ($this->env === 'dev') {
            $viteServerRunning = false;
            $socket = @fsockopen('localhost', 5173, $errno, $errstr, 1);

            if ($socket) {
                $viteServerRunning = true;
                fclose($socket);
            }

            if ($viteServerRunning) {
                return $this->assetDev($entry, $deps);
            }
        }

        return $this->assetProd($entry);
    }

    public function assetDev(string $entry, array $deps): string
    {
        $html = <<<HTML
<script type="module" src="http://localhost:5173/assets/@vite/client"></script>
HTML;

        if (in_array('react', $deps, true)) {
            $html .= <<<HTML
<script type="module">
    import RefreshRuntime from "http://localhost:5173/assets/@react-refresh"
    RefreshRuntime.injectIntoGlobalHook(window)
    window.\$RefreshReg\$ = () => {}
    window.\$RefreshSig\$ = () => (type) => type
    window.__vite_plugin_react_preamble_installed__ = true
</script>
HTML;
        }

        $html .= <<<HTML
<script type="module" src="http://localhost:5173/assets/{$entry}" defer></script>
HTML;

        return $html;
    }

    public function assetProd(string $entry): string
    {
        if ($this->manifestData === null) {
            $this->manifestData = json_decode(file_get_contents($this->manifest), true);
        }

        if (!isset($this->manifestData[$entry])) {
            throw new \RuntimeException("Entry point '{$entry}' not found in Vite manifest");
        }

        $html = '';
        $entryChunk = $this->manifestData[$entry];

        if (isset($entryChunk['css']) && is_array($entryChunk['css'])) {
            foreach ($entryChunk['css'] as $cssFile) {
                $html .= <<<HTML
<link rel="stylesheet" href="/assets/{$cssFile}"/>
HTML;
            }
        }

        $importedChunks = $this->getImportedChunks($entry);

        foreach ($importedChunks as $chunkKey) {
            $chunk = $this->manifestData[$chunkKey];

            if (isset($chunk['css']) && is_array($chunk['css'])) {
                foreach ($chunk['css'] as $cssFile) {
                    $html .= <<<HTML
<link rel="stylesheet" href="/assets/{$cssFile}"/>
HTML;
                }
            }
        }

        $mainFile = $entryChunk['file'];
        $html .= <<<HTML
<script type="module" src="/assets/{$mainFile}" defer></script>
HTML;

        foreach ($importedChunks as $chunkKey) {
            $chunk = $this->manifestData[$chunkKey];

            if (isset($chunk['file'])) {
                $html .= <<<HTML
<link rel="modulepreload" href="/assets/{$chunk['file']}"/>
HTML;
            }
        }

        return $html;
    }

    private function getImportedChunks(string $entry): array
    {
        $importedChunks = [];
        $this->findImportedChunks($entry, $importedChunks);

        return $importedChunks;
    }

    private function findImportedChunks(string $entry, array &$importedChunks): void
    {
        if (isset($this->manifestData[$entry]['imports']) && is_array($this->manifestData[$entry]['imports'])) {
            foreach ($this->manifestData[$entry]['imports'] as $import) {
                if (!in_array($import, $importedChunks, true)) {
                    $importedChunks[] = $import;
                    $this->findImportedChunks($import, $importedChunks);
                }
            }
        }
    }
}
