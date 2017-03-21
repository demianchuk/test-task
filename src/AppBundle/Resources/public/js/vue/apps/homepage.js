window.Vue = require('vue');
require('../config.js');
var Resource = require('vue-resource');
var VueValidator = require('vue-validator');
Vue.use(Resource);
Vue.use(VueValidator);

//add custom validators
require('../vue_custom_validators.js');

//register component
var statisticWidget = require('../components/statisticWidget.vue');

new Vue({
    el: '#statistics',
    data: {
        urlFirst: '',
        urlSecond: ''
    },
    components: {
        'statistic-widget': statisticWidget
    },
    methods: {
        submitForm: function (event) {
            if (this.$validation.valid) {
                //send event to child components
                this.$broadcast('submittedForm', event);
            }
        }
    }
});