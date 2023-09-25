(($, window, document) => {
    'use strict';
    $(() => {
        const BASE_URL =  hsdl_mdt_ajax.api_endpoint// add env endpoint
        const API_ROUTE = {
            "#addToSeries": "series",
            "#fastSubject": "fastSubject",
            "#addToList": "list",
        }

        const getCookie = (cname) => {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for(var i = 0; i <ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        const TOKEN = getCookie("chds"); // get token from cookie

        $('#mdt-toggle-checkbox').on('change', (e) => {
            let checked_val = e.target.value;
            let mdt = '';
            if (checked_val == 1) {
                mdt = 0;
            } else {
                mdt = 1;
            }
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('mdt', mdt);
            history.pushState(null, null, "?" + urlParams.toString());
            location.reload();
        });

        const loader = $(".loading");
        const searchField = $(".search-field");
        const popInnerBody = $(".modal-inner-body");
        const resultList = $(".list-inner");
        const checkboxList = $(".chkbox-list");
        const selectedList = $(".added-details");
        const addedListHolder = $(".added-list");
        const submittingStatus = $(".submitting");
        const toListDataLoader = $("#to-list-loading");
        const toListPopupBody = $("#to-list-popup-inner-wrapper");
        const btnSubmit = $(".popup-buttons .btn-submit");
        const URLparams = new URLSearchParams(window.location.search);
        const did = URLparams.get('did');

        let currentPopUpId = ""
        let handleCurrPopUpSelections = {};
        let toListOriginalDataSet = [];

        // hide eleemts in initial render
        $(".page-template-search-results-detail").find(".chkbox-list, .list-inner, .loading, .added-list, .popup-buttons .btn-submit, .submitting, .server-error-msg, #to-list-loading, #to-list-popup-inner-wrapper").hide();

        // handle series popup open
        $('#to-series-nav-item').on('click', (e) => {
            popInnerBody.show();
            currentPopUpId = "#addToSeries";
        });

        // handle fast subj popup open
        $('#fast-subj-nav-item').on('click', (e) => {
            popInnerBody.show();
            currentPopUpId = "#fastSubject";
        });

        // handle to list popup open
        $('#to-list-nav-item').on('click', (e) => {
            // popInnerBody.show();
            toListDataLoader.show();
            toListPopupBody.hide();
            currentPopUpId = "#addToList";

            loadToListData();
        });

        // Load to list results list
        const loadToListData = () => {
            // Load data
            $.ajax({
                url: `${BASE_URL}/list/branch`,
                type: 'get',
                headers: {
                    Accept: "application/json",
                    "Content-type": "application/json",
                    Authorization: `Bearer ${TOKEN}`
                },
                data: {},
                dataType: "json",
                beforeSend: function () {
                    // $('#loading_indicator').show();
                },
                success: function (response) {
                    console.log("response", response);
                    toListDataLoader.hide();
                    toListPopupBody.show();
                    const {status, data, message} = response;
                    toListOriginalDataSet = data;
                },
                error: function (errorThrown) {
                    toListDataLoader.hide();
                    console.log(errorThrown);
                    const {status, data, message} = errorThrown?.responseJSON;
                    if(message?.code == 400) {
                        $(currentPopUpId + " .server-error-msg").show().text(message?.msg);
                    }
                }
            });
        }

        // handle selected popup search bar
        $(currentPopUpId + ` .search-field`).on('input', (e) => {
            let $this = $(e.currentTarget);
            let elements = $();
            let value = $this.val();

            console.log("currentPopUpId", currentPopUpId, value);

            if(value.length > 2) {
                if(currentPopUpId == "#addToList") {
                    resultList.show();
                    checkboxList.show();
                    // loader.show();
                    let results = toListOriginalDataSet.filter((doc) => {
                        let {ControlledName, ControlledNameId} = doc;
                        let title = ControlledName.toLowerCase();
                        if(title.includes(value.toLowerCase())) {
                            elements = elements.add(`
                            <div class="checkbox-div">
                                <input type="checkbox" data-ind="" name="${ControlledName}" id="${ControlledNameId}">
                                <label for="">${ControlledName}</label>
                            </div>
                            `);
                        }
                        return true;
                    });

                    resultList.append(elements);

                } else {
                    resultList.show();
                    checkboxList.show();
                    loader.show();
                    $.ajax({
                        url: `${BASE_URL}/${API_ROUTE[currentPopUpId]}/?name=${value}`, // or example_ajax_obj.ajaxurl if using on frontend
                        type: 'get',
                        headers: {
                            Accept: "application/json",
                            "Content-type": "application/json",
                            Authorization: `Bearer ${TOKEN}`
                        },
                        data: {},
                        dataType: "json",
                        beforeSend: function () {
                            // $('#loading_indicator').show();
                        },
                        success: function (response) {
                            console.log("response", response);
                            const {status, data, message} = response;
                            loader.hide();
                            if(message?.code == 200) {
                                for(let i = 0; i < data.length; i++) {
                                    const {ControlledName, ControlledNameId} = data[i];
                                    elements = elements.add(`
                                    <div class="checkbox-div">
                                        <input type="checkbox" data-ind="" name="${ControlledName}" id="${ControlledNameId}">
                                        <label for="">${ControlledName}</label>
                                    </div>
                                    `);
                                }
                            }

                            resultList.append(elements);
                        },
                        error: function (errorThrown) {
                            loader.hide();
                            console.log(errorThrown);
                        }
                    });
                }
            } else {
                resultList.empty();
                resultList.hide();
                checkboxList.hide();
                loader.hide();
            }
        });

        // on result select
        $(resultList).on("click", ".checkbox-div input", (e) => {
            $(this).attr('checked', true);

            if(handleCurrPopUpSelections[e.target.id]) {
                delete handleCurrPopUpSelections[e.target.id];
            } else {
                handleCurrPopUpSelections[e.target.id] = e.target.name;
            }

            if(Object.keys(handleCurrPopUpSelections).length) {
                addedListHolder.show();
                btnSubmit.show()
            } else {
                addedListHolder.hide();
                btnSubmit.hide()
            }

            updateSelectedList();
        })

        // delete selected item
        $(selectedList).on("click", ".added-delete", (e) => {
            $(".list-inner").find(`input[id="${e.target.dataset.id}"]`).prop('checked', false);
            delete handleCurrPopUpSelections[e.target.dataset.id];

            updateSelectedList();
        })

        // handle selected item list
        const updateSelectedList = () => {
            let currentSelectedListArr = Object.keys(handleCurrPopUpSelections);
            var elements = $();
            selectedList.empty(); // remove children and re-render

            for(let i = 0; i < currentSelectedListArr.length; i++) {
                let resultID = currentSelectedListArr[i];

                elements = elements.add(`
                    <div class="row">
                        <div class="col-sm-10">
                            <p>${handleCurrPopUpSelections[resultID]}</p>
                        </div>
                        <div class="col-sm-2">
                            <button class="added-delete" data-id="${resultID}">Delete</button>
                        </div>
                    </div>
                `);

            }

            selectedList.append(elements);
        }

        // handle popup close
        $(currentPopUpId + " .modal-header .close").on('click', () => {
            clearPopUp()
        })

        // handle popup close
        $(currentPopUpId + " .btn-cancel").on('click', () => {
            clearPopUp()
        })

        // clear popup on close
        const clearPopUp = () => {
            $(".page-template-search-results-detail").find(".chkbox-list, .list-inner, .loading, .added-list, .popup-buttons .btn-submit, .submitting, .server-error-msg").hide();
            $(currentPopUpId + " .search-field").val("");
            currentPopUpId = "";
            handleCurrPopUpSelections = {};
        }

        // handle selected items submit
        $(btnSubmit).on("click", () => {
            if(Object.keys(handleCurrPopUpSelections).length) {
                submittingStatus.show();
                $(currentPopUpId + " .server-error-msg").text("");

                $.ajax({
                    url: `${BASE_URL}/${API_ROUTE[currentPopUpId]}/relation`,
                    type: 'post',
                    headers: {
                        Accept: "application/json",
                        "Content-type": "application/json",
                        Authorization: `Bearer ${TOKEN}`
                    },
                    data: JSON.stringify({
                        docID: did,
                        cnameIDs: Object.keys(handleCurrPopUpSelections)
                    }),
                    dataType: "json",
                    success: function (response) {
                        console.log("response", response);
                        const {status, data, message} = response;
                        console.log("success message", message);
                        submittingStatus.hide();
                        $(currentPopUpId + " .modal-header .close").click();
                        location.reload();
                    },
                    error: function (errorThrown) {
                        submittingStatus.hide();
                        console.log(errorThrown);
                        const {status, data, message} = errorThrown?.responseJSON;

                        console.log("error message", message);
                        if(message?.code == 400) {
                            $(currentPopUpId + " .server-error-msg").show().text(message?.msg);
                        }
                    }
                });
            }
        })

        //delete icon action on abstract page bottom tag list
        $('.delete-list').on('click', (e) => {
            let listId = e.target.dataset.id;
            let docId = e.target.dataset.docid;
            let data = {
                'nominationID': listId,
                'docID': docId,
            };
            $.ajax({
                url: `${BASE_URL}/document/listed-on`,
                type: 'DELETE',
                data: JSON.stringify(data),
                contentType: 'application/json',
                dataType: "json",
                headers: {
                    Authorization: `Bearer ${TOKEN}`
                },
                success: function (response) {
                    const {status, data, message} = response;
                    if (status == "success") {
                        location.reload();
                    }
                },
                error: function (errorThrown) {
                    console.log(errorThrown);
                }
            });
        });

        //Nomination icon click action
        $('.nav-link-nominate').on('click', (e) => {
            let cr = e.target.dataset.cr;
            let docId = e.target.dataset.docid;
            let data = {
                'nominationID': cr,
                'docID': docId,
            };
            $.ajax({
                url: `${BASE_URL}/nomination/`,
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                dataType: "json",
                headers: {
                    Authorization: `Bearer ${TOKEN}`
                },
                success: function (response) {
                    const {status, data, message} = response;
                    if(message?.code == 200) {
                        location.reload();
                    }
                },
                error: function (errorThrown) {
                    console.log(errorThrown);
                }
            });
        });

        //Nominated delete icon click action
        $('.nav-link-delete').on('click', (e) => {
            let cr = e.target.dataset.cr;
            let docId = e.target.dataset.docid;
            let data = {
                'nominationID': cr,
                'docID': docId,
            };
            $.ajax({
                url: `${BASE_URL}/nomination/`,
                type: 'DELETE',
                data: JSON.stringify(data),
                contentType: 'application/json',
                dataType: "json",
                headers: {
                    Authorization: `Bearer ${TOKEN}`
                },
                success: function (response) {
                    const {status, data, message} = response;
                    if(message?.code == 200) {
                        location.reload();
                    }
                },
                error: function (errorThrown) {
                    console.log(errorThrown);
                }
            });
        });
    });
})(jQuery, window, document); // or even jQuery.noConflict()
