import { FC } from 'react';

interface Option {
  value: string | number
  label: string
}

interface Filter {
  name: string
  label: string
  value: string | number
  onChange: (e: React.ChangeEvent<any>) => void
  options?: Option[]
  type?: 'select' | 'input'
}

interface Props {
  filters: Filter[]
}

const FiltersBar: FC<Props> = ({ filters }) => {
  return (
    <div className="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      {filters.map(filter => (
        <div key={filter.name}>
          <label htmlFor={filter.name} className="block text-sm font-medium text-gray-700 mb-1">
            {filter.label}
          </label>

          {filter.type === 'input'
            ? (
                <input
                  id={filter.name}
                  type="text"
                  value={filter.value}
                  onChange={filter.onChange}
                  className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                />
              )
            : (
                <select
                  id={filter.name}
                  value={filter.value}
                  onChange={filter.onChange}
                  className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                >
                  {filter.options?.map(option => (
                    <option key={option.value} value={option.value}>
                      {option.label}
                    </option>
                  ))}
                </select>
              )}
        </div>
      ))}
    </div>
  );
};

export default FiltersBar;
