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

  return match({
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
    .exhaustive();
}
