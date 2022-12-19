
window.addEventListener('load', () => {

    let well = {
        // 'overview': (E) => overview(E),
        'messages': (E) => messages(E),
        'layer': E => layer(E)
    },
        triggers = document.querySelectorAll("[trigger]");


    triggers.forEach(E => {

        let trigger = E.getAttribute('trigger');
        let trigerRes = document.querySelector("[trigger-res=" + trigger + "]");

        /*
        if (trigger == "overview") {
            overview(trigerRes);
        }*/

        E.addEventListener('click', () => {

            if (trigerRes) {
                let trigerResAll = document.querySelectorAll("[trigger-res]");

                trigerResAll.forEach(E => {
                    E.classList.remove("open");
                });
                trigerRes.classList.add("open");
            }

            triggers.forEach(_E => {
                _E.classList.remove("active");
                _E.removeAttribute('aria-current');
            });

            E.classList.add("active");
            E.setAttribute('aria-current', 'page');


            if (well[trigger]) well[trigger](trigerRes);


        });
    });

    setInterval(() => checkNewMessage(), 1000);
});

function messages(element) {
    try {

        API(1, E => {

            let result = JSON.parse(E);
            element.innerHTML = null;

            element.append(DOM("div", {
                attr : {class: "ps-1 pe-1"},
                inner: (El) => {
                    result.forEach(_E => {

                        El.append(
                            DOM("div", {
                                attr: { class: "message-box", tooltip: _E['date'], read: _E['readed'] },
                                inner: _E['message'],
                                todo: E => {

                                    E.addEventListener("click", () => readMessage(_E));
                                }
                            }).valueOf());

                    });
                }
            }).valueOf());

        });

    } catch (e) {

        console.log(e);
    }
}

let layers = [];

function layer(element) {

    let data = {
        message: element.innerHTML
    };

    readMessage(data);


}

function readMessage(data) {

    layers.forEach(E => {
        E.classList.remove("show");
        setTimeout(() => E.remove(), 250);
    });

    let element = DOM("div", {
        attr: { class: "message-layer" },
        inner: [
            DOM('span', {
                attr: { class: "close" },
                inner: "&times;",
                todo: E => {
                    E.addEventListener("click", () => {
                        element.classList.remove("show");
                        setTimeout(() => element.remove(), 250);
                    })
                }
            }),
            DOM("p", {
                inner: data['message']
            })
        ]
    }).valueOf();

    layers.push(element);
    document.body.append(element);

    setTimeout(() => element.classList.add("show"), 250);

}

function checkNewMessage() {

    try {

        API(2, E => {

            let result = JSON.parse(E);
            if (Number(result['unread']) > 0) {

                let msgElement = document.querySelector("[trigger='messages']");

                if (msgElement) {
                    msgElement.setAttribute("unread", Number(result['unread']));
                }

            } else {

                let msgElement = document.querySelector("[unread]");
                if (msgElement) msgElement.removeAttribute("unread");
            }
        });

    } catch (e) {

        console.log(e);
    }

}

/*
function overview(element) {

    try {
        API(0, E => {

            let result = JSON.parse(E);

            element.innerHTML = null;

            element.append((DOM("div", {
                attr: { class: "text-dark card-body" },
                inner: [
                    DOM("table", {
                        attr: { class: "table" },
                        inner: [
                            DOM("tbody", {
                                inner: (element) => {

                                    Object.keys(result).forEach(E => {

                                        let text = result[E];
                                        element.append(DOM("tr", {
                                            inner: [
                                                DOM("td", {
                                                    inner: E
                                                }),
                                                DOM("td", {
                                                    inner: ":"
                                                }),
                                                DOM("td", {
                                                    inner: text
                                                })
                                            ]
                                        }).valueOf());
                                    });
                                }
                            })
                        ]
                    })
                ]

            })).valueOf());


        });
    } catch (e) {

        console.log(e);
    }
}
*/


function API(kode, callback) {

    try {

        let form = new FormData();
        form.append('kode', kode);

        const req = new XMLHttpRequest();
        req.addEventListener("load", (E) => {
            return callback(E.currentTarget.response);
        });

        req.open("POST", window.location);
        req.setRequestHeader("requested-with", "xhr");
        req.send(form);

    } catch (e) {

        throw new Error(e);
    }
}
