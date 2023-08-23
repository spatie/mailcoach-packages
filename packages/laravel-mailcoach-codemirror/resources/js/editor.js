import { basicSetup, } from 'codemirror'
import {EditorView, keymap} from "@codemirror/view"
import { html } from '@codemirror/lang-html'
import {indentWithTab} from "@codemirror/commands"

/** From https://github.com/mjmlio/mjml-app/blob/master/src/helpers/codemirror-autocomplete-mjml.js */
const tags = {
    'mj-accordion': {
        attrs: {
            'css-class': null,
            'container-background-color': null,
            border: null,
            'font-family': null,
            'icon-align': null,
            'icon-wrapped-url': null,
            'icon-wrapped-alt': null,
            'icon-unwrapped-url': null,
            'icon-unwrapped-alt': null,
            'icon-position': null,
            'icon-height': null,
            'icon-width': null,
            'padding-bottom': null,
            'padding-left': null,
            'padding-right': null,
            'padding-top': null,
            padding: null,
        },
    },
    'mj-accordion-element': {
        attrs: {
            'background-color': null,
            'font-family': null,
            'icon-align': null,
            'icon-wrapped-url': null,
            'icon-wrapped-alt': null,
            'icon-unwrapped-url': null,
            'icon-unwrapped-alt': null,
            'icon-position': null,
            'icon-height': null,
            'icon-width': null,
            'css-class': null,
        },
    },
    'mj-accordion-title': {
        attrs: {
            'background-color': null,
            color: null,
            'font-family': null,
            'font-size': null,
            'padding-bottom': null,
            'padding-left': null,
            'padding-right': null,
            'padding-top': null,
            padding: null,
            'css-class': null,
        },
    },
    'mj-accordion-text': {
        attrs: {
            'background-color': null,
            color: null,
            'font-family': null,
            'font-size': null,
            'padding-bottom': null,
            'padding-left': null,
            'padding-right': null,
            'padding-top': null,
            padding: null,
            'css-class': null,
        },
    },
    'mj-button': {
        attrs: {
            'background-color': null,
            'container-background-color': null,
            border: null,
            'border-bottom': null,
            'border-left': null,
            'border-right': null,
            'border-top': null,
            'border-radius': null,
            'font-style': null,
            'font-size': null,
            'font-weight': null,
            'font-family': null,
            color: null,
            'text-decoration': null,
            'text-transform': null,
            align: null,
            'vertical-align': null,
            'line-height': null,
            href: null,
            rel: null,
            'inner-padding': null,
            padding: null,
            'padding-top': null,
            'padding-bottom': null,
            'padding-left': null,
            'padding-right': null,
            width: null,
            height: null,
            'css-class': null,
        },
    },
    'mj-carousel': {
        attrs: {
            align: null,
            'border-radius': null,
            'background-color': null,
            thumbnails: null,
            'tb-border': null,
            'tb-border-radius': null,
            'tb-hover-border-color': null,
            'tb-selected-border-color': null,
            'tb-width': null,
            'left-icon': null,
            'right-icon': null,
            'icon-width': null,
            'css-class': null,
        },
    },
    'mj-carousel-image': {
        attrs: {
            src: null,
            'thumbnails-src': null,
            href: null,
            rel: null,
            alt: null,
            title: null,
            'css-class': null,
        },
    },
    'mj-class': {
        attrs: {
            name: null,
        },
    },
    'mj-column': {
        attrs: {
            'background-color': null,
            border: null,
            'border-bottom': null,
            'border-left': null,
            'border-right': null,
            'border-top': null,
            'border-radius': null,
            width: null,
            'vertical-align': null,
            'css-class': null,
        },
    },
    'mj-divider': {
        attrs: {
            'border-color': null,
            'border-style': null,
            'border-width': null,
            width: null,
            'container-background-color': null,
            padding: null,
            'padding-top': null,
            'padding-bottom': null,
            'padding-left': null,
            'padding-right': null,
            'css-class': null,
        },
    },
    'mj-group': {
        attrs: {
            width: null,
            'vertical-align': null,
            'background-color': null,
            'css-class': null,
        },
    },
    'mj-font': {
        attrs: {
            href: null,
            name: null,
            'css-class': null,
        },
    },
    'mj-hero': {
        attrs: {
            width: null,
            mode: null,
            height: null,
            'background-width': null,
            'background-height': null,
            'background-url': null,
            'background-color': null,
            'background-position': null,
            padding: null,
            'padding-top': null,
            'padding-right': null,
            'padding-left': null,
            'padding-bottom': null,
            'vertical-align': null,
            'css-class': null,
        },
    },
    'mj-image': {
        attrs: {
            padding: null,
            'padding-top': null,
            'padding-bottom': null,
            'padding-left': null,
            'padding-right': null,
            'container-background-color': null,
            border: null,
            'border-radius': null,
            width: null,
            height: null,
            src: null,
            href: null,
            rel: null,
            alt: null,
            align: null,
            title: null,
            'css-class': null,
        },
    },
    'mj-invoice': {
        attrs: {
            align: null,
            color: null,
            'font-family': null,
            'font-size': null,
            'line-height': null,
            border: null,
            'container-background-color': null,
            padding: null,
            'padding-top': null,
            'padding-bottom': null,
            'padding-left': null,
            'padding-right': null,
            intl: null,
            format: null,
            'css-class': null,
        },
    },
    'mj-invoice-item': {
        attrs: {
            color: null,
            'font-family': null,
            'font-size': null,
            'line-height': null,
            border: null,
            'text-align': null,
            padding: null,
            'padding-top': null,
            'padding-bottom': null,
            'padding-left': null,
            'padding-right': null,
            name: null,
            price: null,
            quantity: null,
            'css-class': null,
        },
    },
    'mj-list': {
        attrs: {
            color: null,
            'font-family': null,
            'font-size': null,
            'line-height': null,
            padding: null,
            'padding-top': null,
            'padding-bottom': null,
            'padding-left': null,
            'padding-right': null,
            'css-class': null,
        },
    },
    'mj-location': {
        attrs: {
            color: null,
            'font-family': null,
            'font-size': null,
            'font-weight': null,
            href: null,
            rel: null,
            padding: null,
            'padding-top': null,
            'padding-bottom': null,
            'padding-left': null,
            'padding-right': null,
            'img-src': null,
            'css-class': null,
        },
    },
    'mj-navbar': {
        attrs: {
            hamburger: null,
            align: null,
            'ico-open': null,
            'ico-close': null,
            'ico-padding': null,
            'ico-padding-top': null,
            'ico-padding-right': null,
            'ico-padding-bottom': null,
            'ico-padding-left': null,
            'ico-align': null,
            'ico-color': null,
            'ico-font-size': null,
            'ico-font-family': null,
            'ico-text-transform': null,
            'ico-text-decoration': null,
            'ico-line-height': null,
            'css-class': null,
        },
    },
    'mj-navbar-link': {
        attrs: {
            color: null,
            'font-family': null,
            'font-size': null,
            'font-style': null,
            'font-weight': null,
            'line-height': null,
            'text-decoration': null,
            'text-transform': null,
            padding: null,
            'padding-top': null,
            'padding-bottom': null,
            'padding-left': null,
            'padding-right': null,
            rel: null,
            'css-class': null,
        },
    },
    'mj-section': {
        attrs: {
            'full-width': null,
            border: null,
            'border-bottom': null,
            'border-left': null,
            'border-right': null,
            'border-top': null,
            'border-radius': null,
            'background-color': null,
            'background-url': null,
            'background-repeat': null,
            'background-size': null,
            'vertical-align': null,
            'text-align': null,
            padding: null,
            'padding-top': null,
            'padding-bottom': null,
            'padding-left': null,
            'padding-right': null,
            direction: null,
            'css-class': null,
        },
    },
    'mj-social': {
        attrs: {
            align: null,
            'border-radius': null,
            'container-background-color': null,
            color: null,
            'font-family': null,
            'font-size': null,
            'font-style': null,
            'font-weight': null,
            'icon-size': null,
            'inner-padding': null,
            'line-height': null,
            mode: null,
            'padding-bottom': null,
            'padding-left': null,
            'padding-right': null,
            'padding-top': null,
            padding: null,
            'table-layout': null,
            'vertical-align': null,
            'css-class': null,
        },
    },
    'mj-social-element': {
        attrs: {
            align: null,
            'background-color': null,
            color: null,
            'border-radius': null,
            'font-family': null,
            'font-size': null,
            'font-style': null,
            'font-weight': null,
            href: null,
            'icon-color': null,
            'icon-size': null,
            'line-height': null,
            name: null,
            'padding-bottom': null,
            'padding-left': null,
            'padding-right': null,
            'padding-top': null,
            padding: null,
            src: null,
            target: null,
            'text-decoration': null,
            'css-class': null,
        },
    },
    'mj-spacer': {
        attrs: {
            height: null,
            'css-class': null,
        },
    },
    'mj-table': {
        attrs: {
            color: null,
            cellpadding: null,
            cellspacing: null,
            'font-family': null,
            'font-size': null,
            'line-height': null,
            'container-background-color': null,
            padding: null,
            'padding-top': null,
            'padding-bottom': null,
            'padding-left': null,
            'padding-right': null,
            width: null,
            'table-layout': null,
            'css-class': null,
        },
    },
    'mj-text': {
        attrs: {
            align: null,
            'background-color': null,
            color: null,
            'container-background-color': null,
            'font-family': null,
            'font-size': null,
            'font-style': null,
            'font-weight': null,
            height: null,
            'letter-spacing': null,
            'line-height': null,
            'padding-bottom': null,
            'padding-left': null,
            'padding-right': null,
            'padding-top': null,
            padding: null,
            'text-decoration': null,
            'text-transform': null,
            'vertical-align': null,
            'css-class': null,
        },
    },
    'mj-wrapper': {
        attrs: {
            'full-width': null,
            border: null,
            'border-bottom': null,
            'border-left': null,
            'border-right': null,
            'border-top': null,
            'border-radius': null,
            'background-color': null,
            'background-url': null,
            'background-repeat': null,
            'background-size': null,
            'vertical-align': null,
            'text-align': null,
            padding: null,
            'padding-top': null,
            'padding-bottom': null,
            'padding-left': null,
            'padding-right': null,
            'css-class': null,
        },
    },
}

window.setupCodeMirror = function (el, value, onChange) {
    return new EditorView({
        doc: value,
        extensions: [
            basicSetup,
            keymap.of([indentWithTab]),
            html({
                extraTags: tags,
            }),
            EditorView.updateListener.of((v) => {
                if (v.docChanged) {
                    onChange(v);
                }
            })
        ],
        parent: el,
    })
}
