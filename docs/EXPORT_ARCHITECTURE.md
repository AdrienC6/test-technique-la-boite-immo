# Documentation de l'Architecture d'Export

## Diagramme de l'Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                     API / CLI / Frontend                        │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ▼
                ┌────────────────────────────────────┐
                │   ExportService (Orchestrateur)    │
                │  - exportPropertyToGateway()       │
                │  - exportPropertyToAllActive()     │
                │  - exportPropertyToGatewayByCode() │
                └────────────┬───────────────────────┘
                     │
        ┌────────────▼────────────────────────┐
        │   ExporterRegistry (Factory)        │
        │  - register()                       │
        │  - get()                            │
        │  - all()                            │
        └────────────┬────────────────────────┘
                     │
     ┌───────────────┼───────────────┐
     │                               │
     ▼                               ▼
┌──────────┐                ┌─────────────────┐
│ SeLoger  │                │ Leboncoin       │
└──────────┘                └─────────────────┘
     │                               │
     └───────────────────────────────┘
                     │
                     ▼
        ┌────────────────────────────────────┐
        │      ExporterInterface             │
        │  - export(Property)                │
        │  - getGatewayCode()                │
        └────────────────────────────────────┘
```

## Composants Principaux

### 1. ExporterInterface
**Fichier :** `src/api/Service/Exporter/ExporterInterface.php`.

Définit le contrat que tous les exporters de plateforme doivent implémenter.

### 2. ExportResult
**Fichier :** `src/api/DTO/ExportResult.php`.

Un DTO qui encapsule les résultats d'export suite à celui-ci.

### 3. Exporters de plateforme
**Fichiers :** 
- `src/api/Service/Exporter/SeLogerExporter.php`
- `src/api/Service/Exporter/LeBonCoinExporter.php`

Chacun implémente `ExporterInterface` et gère les appels API liés aux plateformes externes.

### 4. ExporterRegistry
**Fichier :** `src/api/Service/Exporter/ExporterRegistry.php`.

Un Registry/Factory qui gère tous les exporters enregistrés.

**Exporters enregistrés** configurés dans `config/services.yaml`:
```yaml
App\Service\Exporter\ExporterRegistry:
  calls:
    - register: ['@App\Service\Exporter\SeLogerExporter']
    - register: ['@App\Service\Exporter\LeBonCoinExporter']
```

### 5. ExportService
**Fichier :** `src/api/Service/ExportService.php`

Le service principal qui :
- Coordonne les exports entre les Property et les Gateway
- Récupère l'exporter approprié du registre
- Persiste les enregistrements d'export en base de données
- Fournit les méthodes d'export de haut niveau

**Méthodes Principales :**
- `exportPropertyToGateway(Property, Gateway): Export` - Export vers un gateway spécifique
- `exportPropertyToGatewayByCode(Property, string): Export` - Export par code de gateway
- `exportPropertyToAllActiveGateways(Property): array` - Export vers toutes les gateway actifs

### 7. API
**Fichier :** `src/api/Controller/ExportController.php`

Points d'accès de l'API REST (migration vers GraphQL à prévoir) :
- `POST /api/exports/property/{id}` - Déclencher un export
- `GET /api/exports` - Lister les exports avec filtrage et pagination

### 8. CLI
**Fichier :** `src/api/Command/ExportPropertyCommand.php`

Commande CLI pour les exports manuels ou asynchrones (CRONs à prévoir) :
```bash
# Exporter vers toutes les gateways actifs
php bin/console app:export:property 1 --all

# Exporter vers un gateway spécifique
php bin/console app:export:property 1 --gateway=seloger
```