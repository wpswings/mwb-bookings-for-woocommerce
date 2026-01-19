import { useEffect, useMemo, useState } from '@wordpress/element';

const CHUNK = 4;

export default function BookingsList({ bookings }) {
    const [cardView, setCardView] = useState(false);
    const [status, setStatus] = useState('');
    const [search, setSearch] = useState('');
    const [visibleCount, setVisibleCount] = useState(CHUNK);

    /* ---------------- FILTER LOGIC ---------------- */

    const filteredBookings = useMemo(() => {
        let data = [...bookings];

        // Status filter
        if (status) {
            data = data.filter(order =>
                order.status === status.replace('wc-', '')
            );
        }

        // Search filter
        if (search.trim() && search !== 'all') {
            const q = search.toLowerCase();
            data = data.filter(order =>
                JSON.stringify(order).toLowerCase().includes(q)
            );
        }

        return data;
    }, [bookings, status, search]);

    /* Reset load more when filters change */
    useEffect(() => {
        setVisibleCount(CHUNK);
    }, [status, search]);

    const visibleBookings = filteredBookings.slice(0, visibleCount);

    /* ---------------- HANDLERS ---------------- */

    const handleViewOrder = (url) => {
        if (url) window.location.href = url;
    };

    const loadMore = () => {
        setVisibleCount(prev => prev + CHUNK);
    };

    /* ---------------- RENDER ---------------- */

    return (
        <div className={`wps-bfw_main ${cardView ? 'card-view-order' : ''}`}>

            {/* Toggle view */}
            <div className="wps-bfw_f-s-toggle">
                <label>
                    <input
                        type="checkbox"
                        checked={cardView}
                        onChange={() => setCardView(v => !v)}
                    />
                    Card View
                </label>
            </div>

            {/* Filters */}
            <div className="wps-bfw_filters">
                <select
                    id="wps-bfw_f-order-status"
                    value={status}
                    onChange={e => setStatus(e.target.value)}
                    className={status ? 'changed-status' : ''}
                >
                    <option value="">All</option>
                    <option value="wc-processing">Processing</option>
                    <option value="wc-completed">Completed</option>
                    <option value="wc-cancelled">Cancelled</option>
                </select>

                <input
                    className="wps-bfw_f-search-input"
                    type="text"
                    placeholder="Search orders"
                    value={search}
                    onChange={e => setSearch(e.target.value)}
                />
            </div>

            {/* Orders */}
            <ul className="wps-bfw_orders">
                {visibleBookings.map(order => (
                    <li key={`${order.order_id}-${order.product}`} className={`wc-${order.status}`}>
                        <img src={order.image} alt={order.product} />

                        <h4>{order.product}</h4>
                        <p>{order.booking}</p>
                        <p>{order.total}</p>
                        <p>{order.payment_method}</p>

                        <button
                            className="wps-bfw_od-view"
                            onClick={() => handleViewOrder(order.view_order_url)}
                        >
                            View Order
                        </button>
                    </li>
                ))}
            </ul>

            {/* No Orders */}
            {filteredBookings.length === 0 && (
                <div id="no-orders-msg" style={{ marginTop: 10 }}>
                    No orders found
                </div>
            )}

            {/* Load More */}
            {visibleCount < filteredBookings.length && (
                <button
                    id="wps-load-more"
                    className="wps-load-more"
                    onClick={loadMore}
                >
                    Load more
                </button>
            )}
        </div>
    );
}
