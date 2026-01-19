import { useEffect, useState } from '@wordpress/element';

const ViewToggle = () => {
    const [isCardView, setIsCardView] = useState(false);

    useEffect(() => {
        const el = document.querySelector('.wps-bfw_main');
        if (!el) return;

        el.classList.toggle('card-view-order', isCardView);
    }, [isCardView]);

    return (
        <div className="wps-bfw_f-s-toggle">
            <span>List</span>
            <input
                type="checkbox"
                checked={isCardView}
                onChange={() => setIsCardView(v => !v)}
            />
            <span>Card</span>
        </div>
    );
};

export default ViewToggle;