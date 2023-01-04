var G_TYPE = {
    G_A: (data) =>{
        GA_Modal(data);
    }
}
window.G_ajax = (path, formData = null, callback, method = "post") => {

    let wrapper = DOMCreate("div", {
        attr: {
            class: "ajax"
        },
        inner: "<div></div>"
    }), onError;
    window.document.body.append(wrapper);

    $.ajax({
        url: path,
        method: method,
        data: formData,
        processData: false,
        contentType: false,
        success: function (data, textStatus, xhr) {

            callback(data);
            wrapper.remove();

        },
        complete: function (xhr, textStatus) {

            if (xhr.status !== 200) {
                let code = xhr.status.toString().charAt(0), title, message;

                switch (code) {
                    case "0": 
                        title = "No internet";
                        message = "Kamu tidak tersambung dengan internet";
                        break;
                    case "1":
                        title = "Kesalahan";
                        message = "Ada kesalahan system :(";
                        break;
                    case "3":
                        title = "Kesalahan";
                        message = "Ada kesalahan system :(";
                        break;
                    case "4":
                        title = "Kesalahan";
                        message = "Ada kesalahan system :(";
                        break;
                    case "5":
                        title = "Kesalahan";
                        message = "Ada kesalahan system :(";
                        break;
                }

                new Alert(title, message, "text-danger");
                wrapper.remove();
                if(onError) onError();
            }
        }
    });
    return {
        onError: E => {
            onError = E;
        }
    };
}

function G_submit(data, caller = null) {

    if (caller) caller.disabled = true;
    let fm = new FormData();

    if (data instanceof Array) {
        data.forEach(text => {
            let input, name;

            switch (text.charAt(0)) {
                case "#":
                    input = document.getElementById(text.substring(1, text.length));
                    name = input.getAttribute('name');
                    fm.append(name, input.value);
                    break;
                default: console.error("dont known selector");
            }
        });
    }

    ajax("?", fm, response => {

        caller.removeAttribute('disabled');
        try{
            response = JSON.parse(response);
            if(response.hasOwnProperty('G_type')) {

                G_TYPE[response.G_type](response);

            }

        } catch (exeption) {

            new Alert("ERROR", response, "text-danger");
        
        }
        

    }).onError(() => {

        caller.removeAttribute("disabled");

    });
}

function GA_Modal(data)
{
    let head = DOMCreate("h4", { attr: { class: "text-primary" }, inner: "Result" }),
        body = DOMCreate("div", {
            todo: element => {
                let keys = Object.keys(data);

                let table = DOMCreate("table");

                keys.forEach( el => {

                    let column = DOMCreate("tr", {
                        attr: {style: "border-bottom: solid .5px #f5f5f5; padding: .25em"},
                        inner: [
                            DOMCreate('th', {
                                inner: el
                            }),
                            DOMCreate('td', {
                                attr : { class: "ps-2"},
                                inner: ':'
                            }),
                            DOMCreate('td', {
                                attr : { class: "ps-2"},
                                inner: data[el].toString(),
                                todo : td => {

                                    if(el == "icon") {
                                        let img = DOMCreate('img', {
                                            attr: {width: 35, height: 35, src: data[el].toString(), style: "width:35px;height:35px;object-fit:cover;border-radius:7.5px;"}
                                        });
                                        td.innerHTML = null;
                                        td.append(img);
                                        
                                    }
                                }
                            })
                        ]
                    })
                    table.append(column);
                })

                element.append(table)
                DOMCreate('p', {
                    attr : {class: "mt-3 text-center fw-bold text-primary"},
                    inner: "Terimakasih kontribusi ada sangat di hargai oleh pak Gatoot",
                    todo: el => element.append(el)
                })
                DOMCreate('p', {
                    attr : {class: "text-center text-secondary"},
                    inner: "Pak Gatoot sayang kamu.",
                    todo: el => element.append(el)
                })
            }
        });

    let modal = G_Modal(head, body).init();
    modal.nagative.remove();
    modal.positiveCallBack = () => {
        $(modal).modal("hide");
    }
    modal.getController().show();
}


function Alert(title, message, classList) {
    let head = DOMCreate("h4", { attr: { class: classList }, inner: title }),
        body = DOMCreate("div", {
            inner: [
                DOMCreate("p", {
                    inner: message
                })
            ]
        });

    let modal = G_Modal(head, body).init();
    modal.nagative.remove();
    modal.positiveCallBack = () => {
        $(modal).modal("hide");
    }
    modal.getController().show();
}
function G_Modal(head, body) {
    /**
     * 
     */
    let nagativeCallBack = (modal) => { $(modal).modal('hide'); },
        positiveCallBack = () => { console.log("doing nothing"); },
        nagative = DOMCreate("button", {
            attr: { type: "button", class: "btn btn-secondary" },
            inner: "Tutup"
        }),
        positive = DOMCreate("button", {
            attr: { type: "button", class: "btn btn-secondary" },
            inner: "Konfirmasi"
        });

    let modal = DOMCreate("div", {
        attr: { class: "modal fade", tabindex: "-1", 'aria-hidden': "true", role: "dialog", id: "app-modal" },
        inner: [
            DOMCreate("div", {
                attr: { class: "modal-dialog modal-dialog-centered", role: "document" },
                inner: [

                    /** CONTENT MODAL */
                    DOMCreate("div", {
                        attr: { class: "modal-content" },
                        inner: [

                            /** MODAL HEAD */
                            DOMCreate("div", {
                                attr: { class: "modal-header" },
                                inner: head
                            }),

                            /** MODAL BODY */
                            DOMCreate("div", {
                                attr: { class: "modal-body" },
                                inner: body
                            }),

                            /** MODAL FOOTER */
                            DOMCreate("div", {
                                attr: { class: "modal-footer" },
                                inner: [
                                    /** NEGATIVE BUTTON */
                                    nagative,
                                    /** POSITIVE BUTTON */
                                    positive
                                ]
                            })
                        ]
                    })
                ]
            })
        ]
    })

    modalManager = new bootstrap.Modal(modal, {
        keyboard: false
    })
    modal.positive = positive;
    modal.positiveCallBack = () => positiveCallBack();
    positive.addEventListener("click", () => modal.positiveCallBack());

    modal.nagative = nagative;
    modal.nagativeCallBack = () => nagativeCallBack(modal);
    nagative.addEventListener("click", () => modal.nagativeCallBack())
    modal.getController = () => { return modalManager; };


    $(modal).on('hidden.bs.modal', function (e) {
        setTimeout(() => modal.remove(), 200);
    })

    return {
        init: () => {
            return modal;
        }
    }
}
function DOMCreate(element = "div", data = { attr: {}, inner: [], todo: null }) {
    let a = document.createElement(element),
        attr = data ? data.attr : {},
        inner = data ? data.inner : '',
        todo = data ? data.todo : null;
    if (attr) {
        Object.keys(attr).forEach((b) => {
            let normalize = b.replace(/[A-Z]/g, '-$&').toLowerCase();
            a.setAttribute(normalize, attr[b]);
        });
    }
    a.setInner = (c) => {
        switch (typeof c) {
            case 'string':
                a.innerHTML = c;
                break;
            case 'object':
                if (Array.isArray(c)) {
                    c.forEach((d) => {
                        a.append(d);
                    });
                } else {
                    a.append(c);
                }
                break;
            default:
                console.error("（；￣ェ￣） ERROR: can't set inner because value type is unknown !");
                break;
        }
    };
    a.getInner = () => {
        return a.innerHTML;
    };
    a.onInput = function (minLength, maxLength, exeption = "//g") {
        a.addEventListener("keypress", event => {
            if (event.keyCode != 8 && event.key.match(exeption)) {
                event.preventDefault();
                return false;
            }
        });
        a.addEventListener("keydown", event => {
            if (event.keyCode != 8 && event.key.match(exeption)) {
                event.preventDefault();
                return false;
            }
        });
        a.addEventListener("keyup", event => {
            if (event.keyCode != 8 && event.key.match(exeption)) {
                event.preventDefault();
                return false;
            }
        });
        a.addEventListener("input", () => {
            a.value = a.value.replace(exeption, "");
            a.setAttribute("maxlength", maxLength);
            a.setAttribute("minlength", minLength);
        });
    };

    if (inner) a.setInner(inner);
    if (todo) todo(a);

    return a;
}