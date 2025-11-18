import { ApiResponse } from '@/@types/api';
import { Export } from '@/@types/export';
import { useEffect, useState } from 'react';
import FiltersBar from './modules/filters-bar';
import Pagination from './modules/pagination';
import { usePagination } from '@/hooks/use-pagination';

const STATUS_COLORS: Record<string, string> = {
  pending: 'bg-yellow-100 text-yellow-800',
  in_progress: 'bg-blue-100 text-blue-800',
  completed: 'bg-green-100 text-green-800',
  failed: 'bg-red-100 text-red-800',
};

const STATUS_LABELS: Record<string, string> = {
  pending: 'En attente',
  in_progress: 'En cours',
  completed: 'Complété',
  failed: 'Échoué',
};

export function ExportDashboard() {
  const { page, limit, filters, updateFilter, goToPage, changeLimit } = usePagination<{
    gatewayCode: string
    status: string
    propertyId: string
  }>({
    gatewayCode: '',
    status: '',
    propertyId: '',
  }, 1, 20);

  const [data, setData] = useState<Export[]>([]);
  const [pagination, setPagination] = useState<{ pages: number
    total: number } | null>(null);
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchExports = async () => {
      setIsLoading(true);
      setError(null);

      try {
        const params = new URLSearchParams({
          page: page.toString(),
          limit: limit.toString(),
          ...(filters.gatewayCode ? { gateway_code: filters.gatewayCode } : {}),
          ...(filters.status ? { status: filters.status } : {}),
          ...(filters.propertyId ? { property_id: filters.propertyId } : {}),
        });

        const response = await fetch(`/api/exports?${params}`);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

        const json: ApiResponse = await response.json();
        setData(json.data);
        setPagination(json.pagination);
      } catch (err) {
        setError(err instanceof Error ? err.message : 'Erreur lors du chargement des exports');
      } finally {
        setIsLoading(false);
      }
    };

    fetchExports();
  }, [page, limit, filters]);

  return (
    <div className="p-6 max-w-7xl mx-auto">
      {/* @TODO : To translate with i18n */}
      <h1 className="text-3xl font-bold mb-6 text-gray-900">Tableau de Bord des Exports</h1>

      <FiltersBar
        filters={[
          {
            name: 'gateway',
            label: 'Passerelle',
            value: filters.gatewayCode,
            onChange: e => updateFilter('gatewayCode', e.target.value),
            options: [
              {
                label: 'Toutes les passerelles',
                value: '',
              },
              {
                label: 'SeLoger',
                value: 'seloger',
              },
              {
                label: 'LeBonCoin',
                value: 'leboncoin',
              },
            ],
          },
          {
            name: 'status',
            label: 'Statut',
            value: filters.status,
            onChange: e => updateFilter('status', e.target.value),
            options: [
              {
                label: 'Tous les statuts',
                value: '',
              },
              {
                label: 'En attente',
                value: 'pending',
              },
              {
                label: 'En cours',
                value: 'in_progress',
              },
              {
                label: 'Complété',
                value: 'completed',
              },
              {
                label: 'Échoué',
                value: 'failed',
              },
            ],
          },
          {
            name: 'property_id',
            label: 'ID',
            value: filters.propertyId,
            onChange: e => updateFilter('propertyId', e.target.value),
            type: 'input',
          },
          {
            name: 'limit',
            label: 'Par page',
            value: limit,
            onChange: e => changeLimit(parseInt(e.target.value, 10)),
            options: [
              {
                label: '10',
                value: 10,
              },
              {
                label: '20',
                value: 20,
              },
              {
                label: '50',
                value: 50,
              },
            ],
          },
        ]}
      />

      {error && <div className="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">{error}</div>}

      {isLoading && (
        <div className="flex justify-center items-center h-64">
          <div className="text-lg text-gray-600">Chargement des exports...</div>
        </div>
      )}

      {!isLoading && data.length > 0 && (
        <div className="overflow-x-auto border border-gray-200 rounded-lg">
          <table className="w-full">
            <thead className="bg-gray-50 border-b border-gray-200">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Propriété</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Plateforme</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Statut</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID Externe</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Exporté le</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-200">
              {data.map(exp => (
                <tr key={exp.id} className="hover:bg-gray-50 transition-colors">
                  <td className="px-6 py-4 text-sm text-gray-900">
                    <div>
                      <div className="font-medium">{exp.property.title}</div>
                      <div className="text-xs text-gray-500">
                        ID:
                        {exp.property.id}
                      </div>
                    </div>
                  </td>
                  <td className="px-6 py-4 text-sm text-gray-900 capitalize">
                    {exp.gateway.name}
                    <div className="text-xs text-gray-500">{exp.gateway.code}</div>
                  </td>
                  <td className="px-6 py-4 text-sm">
                    <span className={`inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${STATUS_COLORS[exp.status] || 'bg-gray-100 text-gray-800'}`}>
                      {STATUS_LABELS[exp.status] || exp.status}
                    </span>
                  </td>
                  <td className="px-6 py-4 text-sm text-gray-600">
                    {exp.externalId ? <code className="bg-gray-100 px-2 py-1 rounded text-xs">{exp.externalId}</code> : <span className="text-gray-400">—</span>}
                  </td>
                  <td className="px-6 py-4 text-sm text-gray-600">{new Date(exp.updatedAt).toLocaleString()}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {!isLoading && data.length === 0 && !error && (
        <div className="text-center py-12">
          <p className="text-lg text-gray-600">Aucun export trouvé</p>
        </div>
      )}

      <Pagination
        page={page}
        totalPages={pagination?.pages || 0}
        totalItems={pagination?.total || 0}
        onPageChange={goToPage}
      />
    </div>
  );
}
