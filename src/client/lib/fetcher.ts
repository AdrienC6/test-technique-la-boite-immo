import { ZodSchema } from 'zod';

type FetcherOptions<T> = {
  url: string
  fetchOptions?: RequestInit
  schema: ZodSchema<T>
};

export const fetcher = async <T>({ url, fetchOptions, schema }: FetcherOptions<T>): Promise<T> => {
  const res = await fetch(url, fetchOptions);
  const data = await res.json();

  return schema.parse(data);
};
