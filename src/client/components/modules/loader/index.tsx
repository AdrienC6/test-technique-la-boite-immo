import { FC } from 'react';

const Loader: FC = () => {
  return (
    <div className="flex justify-center items-center h-64">
      <div className="text-lg text-gray-600">Chargement des exports...</div>
    </div>
  );
};

export default Loader;
