window.addEventListener('load', () => {

    let well = {
        'overview': () => {

            overview();
        },
        'messages': () => {

            console.log("messages");
        }
    },
        triggers = document.querySelectorAll("[trigger]");

    triggers.forEach(E => {

        let trigger = E.getAttribute('trigger');
        let trigerRes = document.querySelector("[trigger-res=" + trigger + "]");

        E.addEventListener('click', () => {

            if(trigerRes) {
                let trigerResAll = document.querySelectorAll("[trigger-res]");

                trigerResAll.forEach( E => {
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

            well[trigger]();

        });
    });

});



function overview() {


}