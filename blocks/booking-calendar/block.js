const { registerBlockType } = wp.blocks;
const { TextControl } = wp.components;
const { useBlockProps } = wp.blockEditor;

registerBlockType('wpswings/booking-calendar', {
    title: 'Booking Calendar',
    icon: 'calendar',
    category: 'widgets',
    attributes: {
        id: {
            type: 'number',
            default: 0
        }
    },
    edit: function (props) {
        const blockProps = useBlockProps();

        return wp.element.createElement('div', blockProps,
            wp.element.createElement(TextControl, {
                label: 'Booking Calendar ID',
                value: props.attributes.id,
                type: 'number',
                onChange: function (value) {
                    props.setAttributes({ id: parseInt(value) || 0 });
                },
                placeholder: 'Enter Booking Calendar ID'
            }),
            wp.element.createElement('p', {}, `Shortcode Output: [bookable_booking_calendar id="${props.attributes.id}"]`)
        );
    },
    save: function (props) {
        const blockProps = useBlockProps.save();
        const id = props.attributes.id;

        return wp.element.createElement(
            'div',
            blockProps,
            `[bookable_booking_calendar id="${id}"]`
        );
    }
});
