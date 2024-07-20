const ms_custom_settings = window.wc.wcSettings.getSetting( 'ms_custom_gateway_data', {} );
const ms_custom_label = window.wp.htmlEntities.decodeEntities( ms_custom_settings.title ) || window.wp.i18n.__( 'Custom Gateway in block mode', 'woocommerce' );

const { createElement } = window.wp.element;

const CustomButtons = (props) => {
    // Create a ref for the hidden input field
    const inputRef = React.useRef(null);

    // Define separate onClick functions for the buttons
    const handleCashButtonClick = (event) => {
        event.preventDefault();

        if (inputRef.current) {
            inputRef.current.value = 'Button One Clicked';

            // Optionally, you can also trigger a change event to notify WooCommerce
            const changeEvent = new Event('change', { bubbles: true });
            inputRef.current.dispatchEvent(changeEvent);
        }
    };

    const handleCreditButtonClick = (event) => {
        event.preventDefault();  // Prevent the default action (form submission or page reload)

        if (inputRef.current) {
            inputRef.current.value = 'Button two Clicked';

            // Optionally, you can also trigger a change event to notify WooCommerce
            const changeEvent = new Event('change', { bubbles: true });
            inputRef.current.dispatchEvent(changeEvent);
        }
    };

    // Create the button elements and the hidden input field using createElement
    return createElement(
        'div',
        { className: 'custom-buttons-container' },
        createElement(
            'button',
            {
                onClick: handleCashButtonClick,  // Assign the click handler for Cash
                className: 'button-class-1',
                style: { backgroundColor: 'red', color: 'white' }
            },
            'Cash'  // Text for the Cash button
        ),
        createElement(
            'button',
            {
                onClick: handleCreditButtonClick,  // Assign the click handler for Credit
                className: 'button-class-2',
                style: { backgroundColor: 'green', color: 'white' }
            },
            'Credit'  // Text for the Credit button
        ),
        createElement(
            'input',
            {
                type: 'string',
                name: 'woocommerce-payment-sama-custom-input',
                id: 'woocommerce-payment-sama-custom-input',
                value: 'custom input default value',
                ref: inputRef
            }
        )
    );
};

const Ms_custom_Block_Gateway = {
    name: 'WC_ms_custom',
    label: ms_custom_label,
    content: createElement(CustomButtons),
    edit: createElement(CustomButtons),
    canMakePayment: () => true,
    ariaLabel: ms_custom_label,
    supports: {
        features: ms_custom_settings.supports,
    },
};
window.wc.wcBlocksRegistry.registerPaymentMethod( Ms_custom_Block_Gateway );
