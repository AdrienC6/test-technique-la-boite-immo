export const EXPORTS_FILTERS_OPTIONS = {
  limit: [

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
  status: [
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
  gateway: [
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
};
