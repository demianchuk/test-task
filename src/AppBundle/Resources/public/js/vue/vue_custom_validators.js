Vue.validator('url', function (val) {
    return /^(https?):\/\/github.com\/([\/\w \.-]*)*\/?$/.test(val);
});