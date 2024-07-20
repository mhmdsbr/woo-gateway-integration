const ms_custom_settings = window.wc.wcSettings.getSetting( 'ms_custom_gateway_data', {} );
const ms_custom_label = window.wp.htmlEntities.decodeEntities( ms_custom_settings.title ) || window.wp.i18n.__( 'Custom Gateway in block mode', 'woocommerce' );
const ms_custom_Content = () => {
    return window.wp.htmlEntities.decodeEntities( ms_custom_settings.description || '' );
};
const Ms_custom_Block_Gateway = {
    name: 'WC_ms_custom',
    label: ms_custom_label,
    content: Object( window.wp.element.createElement )( ms_custom_Content, null ),
    edit: Object( window.wp.element.createElement )( ms_custom_Content, null ),
    canMakePayment: () => true,
    ariaLabel: ms_custom_label,
    supports: {
        features: ms_custom_settings.supports,
    },
};
window.wc.wcBlocksRegistry.registerPaymentMethod( Ms_custom_Block_Gateway );