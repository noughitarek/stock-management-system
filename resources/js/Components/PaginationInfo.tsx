import React from 'react';

interface PaginationInfoProps {
  start: number;
  end: number;
  total: number;
}

const PaginationInfo: React.FC<PaginationInfoProps> = ({ start, end, total }) => {
  return (
    <div className="hidden xl:block mx-auto text-slate-500">
        Affichage de {start} à {end} sur {total} entrées
    </div>
  );
};

export default PaginationInfo;
