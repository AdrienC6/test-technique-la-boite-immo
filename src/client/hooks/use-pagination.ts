import { useState } from 'react';

export function usePagination<TFilters = Record<string, string | number>>(initialFilters: TFilters = {} as TFilters, initialPage = 1, initialLimit = 20) {
  const [page, setPage] = useState(initialPage);
  const [limit, setLimit] = useState(initialLimit);
  const [filters, setFilters] = useState<TFilters>(initialFilters);

  const updateFilter = <K extends keyof TFilters>(key: K, value: TFilters[K]) => {
    setFilters(prev => ({
      ...prev,
      [key]: value,
    }));
    setPage(1);
  };

  const goToPage = (newPage: number) => setPage(newPage);

  const changeLimit = (newLimit: number) => {
    setLimit(newLimit);
    setPage(1);
  };

  return {
    page,
    limit,
    filters,
    updateFilter,
    goToPage,
    changeLimit,
  };
}
