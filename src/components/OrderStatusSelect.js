const OrderStatusSelect = ({ value, onChange }) => {
    const statuses = window.wpsBfwData?.orderStatuses || {};

    return (
        <select
            id="wps-bfw_f-order-status"
            value={value}
            onChange={(e) => onChange(e.target.value)}
        >
            <option value="">{wpsBfwData.allOrdersText}</option>

            {Object.entries(statuses).map(([key, label]) => (
                <option key={key} value={key}>
                    {label}
                </option>
            ))}
        </select>
    );
};

export default OrderStatusSelect;
