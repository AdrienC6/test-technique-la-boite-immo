import { AppSidebar } from '@/components/app-sidebar';
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbList,
  BreadcrumbPage,
} from '@/components/ui/breadcrumb';
import { Separator } from '@/components/ui/separator';
import {
  SidebarInset,
  SidebarProvider,
  SidebarTrigger,
} from '@/components/ui/sidebar';
import { useTranslation } from 'react-i18next';
import useSWR from 'swr';
import { z } from 'zod';
import { fetcher } from '@/lib/fetcher';
import { match, P } from 'ts-pattern';

const HelloWorldResponseSchema = z.object({ message: z.string() });

type HelloWorldResponse = z.infer<typeof HelloWorldResponseSchema>;

export function Index() {
  const { t } = useTranslation();
  const { data, error, isLoading } = useSWR<HelloWorldResponse>({
    url: '/hello-world',
    schema: HelloWorldResponseSchema,
  }, fetcher);

  return (
    <SidebarProvider>
      <AppSidebar />
      <SidebarInset>
        <header className="flex h-16 shrink-0 items-center gap-2">
          <div className="flex items-center gap-2 px-4">
            <SidebarTrigger className="-ml-1" />
            <Separator orientation="vertical" className="mr-2 h-4" />
            <Breadcrumb>
              <BreadcrumbList>
                <BreadcrumbItem>
                  <BreadcrumbPage>{t('Accueil')}</BreadcrumbPage>
                </BreadcrumbItem>
              </BreadcrumbList>
            </Breadcrumb>
          </div>
        </header>

        <div className="flex flex-col gap-4 p-4">
          {match({
            isLoading,
            error,
            data,
          })
            .with(
              {
                isLoading: true,
                data: P.nullish,
                error: P.nullish,
              },
              () => <div className="w-full bg-accent h-8 animate-pulse rounded-md"></div>,
            )
            .with(
              { error: P.not(P.nullish) },
              ({ error }) => error.message,
            )
            .with(
              { data: P.not(P.nullish) },
              ({ data }) => <p>{data.message}</p>,
            )
            .with(
              {
                isLoading: false,
                data: P.nullish,
                error: P.nullish,
              },
              () => <p>{t('Aucune donnée disponible')}</p>,
            )
            .exhaustive()}
        </div>
      </SidebarInset>
    </SidebarProvider>
  );
}
