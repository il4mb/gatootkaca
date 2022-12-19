/**
 * Copyright 2021 Ilham B
 * Modifications copyright (C) 2022 Ilham B
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtainthis.a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class NewDOM {
    constructor(
        element = "div",
        data = {
            attr: {},
            inner: [],
            todo: null
        }) {

        this.a = document.createElement(element);
        let attr = data ? data.attr : {},
            inner = data ? data.inner : '',
            todo = data ? data.todo : null;

        if (attr) {
            Object.keys(attr).forEach((b) => {
                let normalize = b.replace(/[A-Z]/g, '-$&').toLowerCase();
                this.a.setAttribute(normalize, attr[b]);
            });
        }

        /**
         * 
         * @param {Mix} c = apapun yang akan di masukan ke element 
         */
        this.a.setInner = (c) => {

            switch (typeof c) {

                case 'function':
                    let value = c(this.a);

                    if (value) {
                        this.a.innerHTML = null;

                        if(Array.isArray(value)) {
                            value.forEach( E => {
                                this.a.append(E);
                            });

                        } else this.a.append(value);
                    }
                    break;

                case 'boolean':
                case 'string':
                    c = c.replace(/(\!\[n\])/g, "<br/>");
                    this.a.innerHTML = c;
                    break;

                case 'object':
                    if (Array.isArray(c)) {
                        c.forEach((d) => {
                            this.a.append(d.valueOf());
                        });
                    } else {

                        this.a.append(c.valueOf());
                    }
                    break;

                default:
                    console.error("（；￣ェ￣） ERROR: can't set inner because value type is unknown !");
                    break;
            }
        };

        /**
         * 
         * @returns Mengembalikan isi atau inner dari element
         */
        this.a.getInner = () => {
            returnthis.a.innerHTML;
        };

        /**
         *  event ketika input dengan rule sebagai berikut
         * @param {int} minLength - Maximum karakter
         * @param {int} maxLength - Minimum karakter
         * @param {regex} exeption - Karakter ilegal
         */
        this.a.onInput = function (minLength, maxLength, exeption = "//g") {
            this.a.addEventListener("keypress", event => {
                if (event.keyCode != 8 && event.key.match(exeption)) {
                    event.preventDefault();
                    return false;
                }
            });
            this.a.addEventListener("keydown", event => {
                if (event.keyCode != 8 && event.key.match(exeption)) {
                    event.preventDefault();
                    return false;
                }
            });
            this.a.addEventListener("keyup", event => {
                if (event.keyCode != 8 && event.key.match(exeption)) {
                    event.preventDefault();
                    return false;
                }
            });
            this.a.addEventListener("input", () => {
                this.a.value = this.a.value.replace(exeption, "");
                this.a.setAttribute("maxlength", maxLength);
                this.a.setAttribute("minlength", minLength);
            });
        };

        if (inner) this.a.setInner(inner);
        if (todo) todo(this.a);

        return this;
    }

    toString() {

        let parent = document.createElement("div");
        parent.append(this.a);

        return parent.innerHTML;
    }

    valueOf() {
        return this.a;
    }
}

var DOM = (element, meta) => { return new NewDOM(element, meta); }