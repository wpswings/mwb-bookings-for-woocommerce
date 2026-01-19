import { createRoot } from '@wordpress/element';
import App from './components/App';

document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('mwb-booking-root');
    if (el) {
        createRoot(el).render(<App />);
    }
});
