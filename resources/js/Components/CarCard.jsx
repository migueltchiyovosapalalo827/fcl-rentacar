import { Link } from '@inertiajs/react';

function formatPrice(value) {
    return Number(value || 0).toLocaleString('pt-PT', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

export default function CarCard({ car, compact = false }) {
    const statusClass =
        car.status === 'disponivel'
            ? 'bg-green-100 text-green-800'
            : car.status === 'alugado'
              ? 'bg-amber-100 text-amber-800'
              : 'bg-gray-100 text-gray-700';

    return (
        <div className="flex flex-col overflow-hidden rounded-lg bg-white shadow-md transition hover:shadow-lg">
            <div className="relative flex h-48 items-center justify-center bg-gray-200">
                {car.cover_url ? (
                    <img
                        src={car.cover_url}
                        alt={`${car.brand} ${car.model}`}
                        className="h-full w-full object-cover"
                    />
                ) : (
                    <span className="text-gray-400">Sem Imagem</span>
                )}
                <span
                    className={`absolute right-3 top-3 inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ${statusClass}`}
                >
                    {car.status_label}
                </span>
            </div>
            <div className="flex flex-1 flex-col p-6">
                {car.category_label && (
                    <p className="mb-1 text-xs uppercase tracking-wide text-gray-500">
                        {car.category_label}
                    </p>
                )}
                <h3 className="mb-1 text-xl font-semibold">
                    <Link
                        href={route('public.cars.show', car.id)}
                        className="hover:text-blue-600"
                    >
                        {car.brand} {car.model}
                    </Link>
                </h3>
                <p className="mb-3 text-sm text-gray-600">
                    {car.year ?? 'Ano n/d'} ·{' '}
                    {car.seats ? `${car.seats} lugares` : 'Capacidade n/d'} ·{' '}
                    {car.transmission_label ?? 'Transmissão n/d'}
                </p>
                <p className="mb-4 text-2xl font-bold text-blue-600">
                    {formatPrice(car.price_per_day)}{' '}
                    <span className="text-sm font-medium text-gray-500">
                        AOA/dia
                    </span>
                </p>
                <div className="mt-auto flex gap-2">
                    <Link
                        href={route('public.cars.show', car.id)}
                        className="flex-1 rounded border border-blue-600 px-4 py-2 text-center font-medium text-blue-600 hover:bg-blue-50"
                    >
                        Ver detalhes
                    </Link>
                    {!compact && car.status === 'disponivel' && (
                        <Link
                            href={route('public.reservations.create', {
                                car_id: car.id,
                            })}
                            className="flex-1 rounded bg-blue-600 px-4 py-2 text-center font-medium text-white hover:bg-blue-700"
                        >
                            Reservar
                        </Link>
                    )}
                </div>
            </div>
        </div>
    );
}
