import { useEffect, useState, useMemo } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import BookingsList from './BookingsList';
import ViewToggle from './ViewToggle';
import OrderStatusSelect from './OrderStatusSelect';

import {
    StarIcon,
    SearchIcon,
    MapIcon,
    CalendarIcon,
    ArrowIcon
} from './icons';

export default function App() {

    const [bookings, setBookings] = useState([]);
    const [loading, setLoading] = useState(true);
    const [searchQuery, setSearchQuery] = useState('');

    const [isCardView, setIsCardView] = useState(false);

    // for filtering status
    const [statusFilter, setStatusFilter] = useState('');

   const filteredBookings = useMemo(() => {
    let data = bookings;

    // Status filter
    if (statusFilter) {
        data = data.filter(
            (order) => order.status === statusFilter
        );
    }

    // Search filter
    if (searchQuery.trim()) {
        const q = searchQuery.toLowerCase();

        data = data.filter(order =>
            Object.values(order)
                .join(' ')
                .toLowerCase()
                .includes(q)
        );
    }
    

    return data;
}, [bookings, statusFilter, searchQuery]);



    // for load more button
    const CHUNK = 4;
    const [visibleCount, setVisibleCount] = useState(CHUNK);

   useEffect(() => {
    setVisibleCount(CHUNK);
}, [statusFilter, searchQuery]);
//resewt load more when filter changes

    const visibleBookings = useMemo(() => {
    return filteredBookings.slice(0, visibleCount);
}, [filteredBookings, visibleCount]);//sliced bookings to show

    const handleLoadMore = () => {
        setVisibleCount(prev => prev + CHUNK);
    };

const searchPlaceholder =
    `Showing ${visibleBookings.length} of ${filteredBookings.length} orders`;


useEffect(() => {
    console.log('Selected status:', statusFilter);
}, [statusFilter]);

    useEffect(() => {
        apiFetch({ path: '/wps-bfw/v1/bookings' })
            .then(data => {
                setBookings(data);
                setLoading(false);
            });
    }, []);
    

    if (loading) {
        return <p>Loading bookings...</p>;
    }

    return (
        <div className="wps-bfw_main" id="wps-bfw_main">

            {/* Header */}
            <div className="wps-bfw_header" >
                <h3 className="wps-bfw_h-title">Bookings Dashboard</h3>
                <p className="wps-bfw_h-desc">
                    View, manage, edit, delete, and transfer bookings seamlessly.
                </p>
            </div>

            {/* Filters */}
            <div className="wps-bfw_filters">
                <OrderStatusSelect
                value={statusFilter}
                onChange={setStatusFilter}
            />
                {/* <OrderStatusSelect /> */}
                {/* <select className="wps-bfw_f-order-status">
                    <option value="">All orders</option>
                </select> */}

                <div className="wps-bfw_f-search-box">
                    <SearchIcon />
                    {/* <input
                        type="text"
                        placeholder="Showing all orders"
                        className="wps-bfw_f-search-input"
                    /> */}
                    <input
    type="text"
    // placeholder="Showing all orders"
    placeholder={searchPlaceholder}
    className="wps-bfw_f-search-input"
    value={searchQuery}
    onChange={(e) => setSearchQuery(e.target.value)}
/>

                    <ViewToggle />
                </div>
            </div>

            {/* Orders */}
            <div className="wps-bfw_orders">
            <ul>
                {visibleBookings.map((item, index) => (
                    <li className={item.status}>
                        <img src={item.image} alt="Room" />

                        <div className="wps-bfw_o-desc">

                            <div className="wps-bfw_od-header">
                                <a href={item.product_url} className="h4 wps-bfw_od-title">
                                    {item.product}
                                </a>

                                {item.product_rating !=0 && (
                                    <div className="wps-bfw_odh-rate">
                                        <StarIcon />
                                        <span>{item.product_rating}</span>
                                    </div>
                                )}
                            </div>

                            <div className="wps-bfw_od-loc-date">
                                <a href="#" className="wps-bfw_od-locate">
                                    Order ID: {item.order_id}
                                </a>

                                <div className="wps-bfw_od-locate wps-bfw_od-date">
                                    <CalendarIcon />
                                    <span>{item.booking}</span>
                                </div>
                            </div>

                            <div className="wps-bfw_od-loc-status">
                                <a href="#" className="wps-bfw_od-locate">
                                    Status: {item.status}
                                </a>
                            </div>

                            <div className="p wps-bfw_od-desc">
                                {item.short_desc}
                            </div>

                            <div className="wps-bfw_od-pay-cancel">
                                <div className="wps-bfw_od-pay">
                                    Payment Method:
                                    <span className="offline">
                                        {item.payment_method}
                                    </span>
                                </div>

                                {item.can_cancel && item.cancel_allowed && (
                                    <input
                                        type="button"
                                        value="Cancel Booking"
                                        id="wps_bfw_cancel_order"
                                        data-product ={item.product_id}
                                        data-order ={item.order_id}
                                        className="wps-bfw_od-d-can-input"
                                        onClick={() => handleCancel(item)}
                                    />
                                )}
                            </div>

                            <div className="wps-bfw_od-view-price">
                                <input
                                    type="button"
                                    value="View Order"
                                    className="wps-bfw_od-view"
                                    onClick={() => window.location.href = item.view_order_url}
                                />

                                {item.calendar_url && (
                                    <a
                                        href={item.calendar_url}
                                        className="button"
                                        style={{ marginBottom: '5px' }}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        + Add to Google Calendar
                                    </a>
                                )}

                                <div className="wps-bfw_od-price">
                                    {item.total}
                                </div>
                            </div>

                        </div>
                    </li>

                ))}
            </ul>
            {filteredBookings.length === 0 && (
    <div id="no-orders-msg" style={{ marginTop: '10px' }}>
        No orders found
    </div>
)}

            {visibleCount < filteredBookings.length && (
                <button
                id ="wps-load-more-btn"
                    className="wps-load-more"
                    onClick={() => setVisibleCount(v => v + CHUNK)}
                >
                    Load more
                </button>
            )}

            </div>

        </div>
    );
}

