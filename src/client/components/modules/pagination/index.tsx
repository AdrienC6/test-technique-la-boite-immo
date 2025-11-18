import { FC } from 'react';

interface PaginationProps {
  page: number
  totalPages: number
  totalItems?: number
  onPageChange: (page: number) => void
}

const Pagination: FC<PaginationProps> = ({
  page,
  totalPages,
  totalItems,
  onPageChange,
}) => {
  if (totalPages <= 1) return null;

  return (
    <div className="mt-6 flex justify-center items-center gap-4">
      <button
        onClick={() => onPageChange(Math.max(1, page - 1))}
        disabled={page === 1}
        className="px-4 py-2 bg-blue-500 text-white rounded-md disabled:bg-gray-300 disabled:cursor-not-allowed hover:bg-blue-600 transition-colors"
      >
        Précédent
      </button>

      <div className="text-sm text-gray-700">
        Page
        {' '}
        <span className="font-semibold">{page}</span>
        {' '}
        sur
        {' '}
        <span className="font-semibold">{totalPages}</span>
        {totalItems !== undefined && (
          <span className="ml-4 text-gray-500">
            (
            {totalItems}
            {' '}
            total)
          </span>
        )}
      </div>

      <button
        onClick={() => onPageChange(Math.min(totalPages, page + 1))}
        disabled={page === totalPages}
        className="px-4 py-2 bg-blue-500 text-white rounded-md disabled:bg-gray-300 disabled:cursor-not-allowed hover:bg-blue-600 transition-colors"
      >
        Suivant
      </button>
    </div>
  );
};

export default Pagination;
