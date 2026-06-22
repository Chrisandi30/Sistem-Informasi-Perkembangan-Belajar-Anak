<style>
    /* Samakan Search dengan lebar card hanya pada tablet dan ponsel. */
    @media (max-width: 1000px) {
        html body .content-inner .responsive-search-field {
            display: block !important;
            width: 100% !important;
            min-width: 0 !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
            box-sizing: border-box !important;
        }

        html body .content-inner .responsive-search-field > div,
        html body .content-inner .responsive-search-field .form-control {
            display: block !important;
            width: 100% !important;
            min-width: 0 !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
    }
</style>