<script>
    //when using this component remember to include template file: statistic_vue_template.html.twig
    export default {
        template: '#statistic-widget-template',
        props: ['urlParent', 'typeValue'],
        data: function() {
            return {
                urlValue: false,
                stars: false,
                subscribers: false,
                forks: false,
                openPullRequests: false,
                closedPullRequests: false,
                lastUpdate: false,
                lastRelease: false,
                pullRequestMerge: false,
                score: 0,
                class: false,
                visible: false,
                loader: 'disabled',
                userName: false,
                repoName: false,
                errorResponse: false,
                yellowBarrier: 30,
                greenBarrier: 70
            }
        },
        methods: {
            getStatisticData: function () {
                //reset errors
                this.errorResponse = false;

                //show panels with loaders
                this.visible = true;
                this.loader = 'active';

                //get userName and repoName from url
                this.parseUrl(this.urlParent);

                //get statistics for first url
                this.$http.get(Routing.generate('api_get_statistic_all_statistics', {userName: this.userName, repoName: this.repoName}))
                    .then(
                        function(response) {
                            //populate model with response data
                            this.populateModel(response.data);
                            //set url to panel
                            this.urlValue = this.urlParent;

                            //show progress bar info according score
                            $('#prb-' + this.typeValue).progress({percent: this.score});

                            //hide loader
                            this.loader = '';
                        },
                        function(errorResponse) { //error
                            this.errorResponse = true;
                            this.visible = false;
                        }
                    );
            },
            parseUrl: function(url) {
            var parser = document.createElement('a');
            parser.href = url;

            var params = parser.pathname.replace(/^\/+/g, '').split("/");

            this.userName = params[0];
            this.repoName = params[1];
        },
            populateModel: function(data) {
                this.stars = data.stars;
                this.forks = data.forks;
                this.subscribers = data.subscribers;
                this.openPullRequests = data.open_pull_requests;
                this.closedPullRequests = data.closed_pull_requests;
                this.lastUpdate = moment(data.last_update_date).format('LLL');
                this.lastRelease = moment(data.last_release_date).format('LLL');
                this.pullRequestMerge = moment(data.last_pull_request_merge_date).format('LLL');
                this.score = data.score;
                //set progress bar color state
                this.class = 'red';

                if (this.score >= this.yellowBarrier) {
                    this.class = 'yellow';
                }

                if (this.score >= this.greenBarrier) {
                    this.class = 'green';
                }
            }
        },
        events: {
            /**
             * Handle form submitted event
             */
            submittedForm: function (event) {
                this.getStatisticData();
            }
        }
    };
</script>
