angular.module('currency_filters', []).filter('currency', function() {
    return function(number, currencyCode) {
      var aa =  accounting.formatMoney(number, currencyCode, 2, ",", ".");
      return aa;
    };
  });