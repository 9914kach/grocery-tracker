import { Link } from '@inertiajs/react';

export default function StatsCard({ label, value, href }) {
    const content = (
        <div className="rounded-lg border border-gray-200 bg-white p-5 transition-colors hover:border-gray-300">
            <p className="text-xs font-medium uppercase tracking-wide text-gray-400">
                {label}
            </p>
            <p className="mt-1 text-2xl font-semibold text-gray-900">{value}</p>
        </div>
    );

    if (href) {
        return <Link href={href}>{content}</Link>;
    }

    return content;
}
