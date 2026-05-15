(function ($, elementor,elementorCommon) {

        ControlSelect2Query = elementor.modules.controls.Select2.extend({
            cache: null,
            isTitlesReceived: false,

            getQueryData: function getQueryData() {
                const autocomplete = this.model.get('autocomplete');
                return {
                    autocomplete
                };
            },

            getSelect2DefaultOptions: function getSelect2DefaultOptions() {
                var self = this;
                return jQuery.extend(elementor.modules.controls.Select2.prototype.getSelect2DefaultOptions.apply(this, arguments), {
                    ajax: {
                        transport: function transport(params, success, failure) {

                            var data = {},
                                action = 'panel_posts_control_filter_hitboox_query';
                            data = self.getQueryData();
                            data.q = params.data.q;
                            return elementorCommon.ajax.addRequest(action, {
                                data: data,
                                success: success,
                                error: failure
                            });
                        },
                        data: function data(params) {
                            return {
                                q: params.term,
                                page: params.page
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function escapeMarkup(markup) {
                        return markup;
                    },
                    minimumInputLength: 1
                });
            },
            getValueTitles: function getValueTitles() {
                var self = this,
                    data = {}

                var ids = this.getControlValue(),
                    action = 'query_control_value_hitboox_query';

                if (!_.isArray(ids)) {
                    ids = [ids];
                }

                elementorCommon.ajax.loadObjects({
                    action: action,
                    ids: ids,
                    data: data,
                    before: function before() {
                        self.addControlSpinner();
                    },
                    success: function success(ajaxData) {
                        self.isTitlesReceived = true;
                        self.model.set('options', ajaxData);
                        self.render();
                    }
                });
            },
            addControlSpinner: function addControlSpinner() {
                this.ui.select.prop('disabled', true);
                this.$el.find('.elementor-control-title').after('<span class="elementor-control-spinner">&nbsp;<i class="eicon-spinner eicon-animation-spin"></i>&nbsp;</span>');
            },
            onReady: function onReady() {
                if (!this.isTitlesReceived) {
                    this.getValueTitles();
                }
            }
        });

        elementor.addControlView('hitboox_query', ControlSelect2Query);

})(jQuery, window.elementor,window.elementorCommon);