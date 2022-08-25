import CodeMirror from "codemirror/lib/codemirror";

window.CodeMirror = CodeMirror;

import 'codemirror/addon/mode/overlay';
import 'codemirror/addon/edit/continuelist';
import 'codemirror/addon/display/placeholder';
import 'codemirror/addon/selection/mark-selection';
import 'codemirror/addon/search/searchcursor';

import 'codemirror/mode/clike/clike';
import 'codemirror/mode/cmake/cmake';
import 'codemirror/mode/css/css';
import 'codemirror/mode/diff/diff';
import 'codemirror/mode/django/django';
import 'codemirror/mode/dockerfile/dockerfile';
import 'codemirror/mode/gfm/gfm';
import 'codemirror/mode/go/go';
import 'codemirror/mode/htmlmixed/htmlmixed';
import 'codemirror/mode/http/http';
import 'codemirror/mode/javascript/javascript';
import 'codemirror/mode/jinja2/jinja2';
import 'codemirror/mode/jsx/jsx';
import 'codemirror/mode/markdown/markdown';
import 'codemirror/mode/nginx/nginx';
import 'codemirror/mode/pascal/pascal';
import 'codemirror/mode/perl/perl';
import 'codemirror/mode/php/php';
import 'codemirror/mode/protobuf/protobuf';
import 'codemirror/mode/python/python';
import 'codemirror/mode/ruby/ruby';
import 'codemirror/mode/rust/rust';
import 'codemirror/mode/sass/sass';
import 'codemirror/mode/shell/shell';
import 'codemirror/mode/sql/sql';
import 'codemirror/mode/stylus/stylus';
import 'codemirror/mode/swift/swift';
import 'codemirror/mode/vue/vue';
import 'codemirror/mode/xml/xml';
import 'codemirror/mode/yaml/yaml';

import './EasyMDE.js';

CodeMirror.commands.tabAndIndentMarkdownList = function (cm) {
    var ranges = cm.listSelections();
    var pos = ranges[0].head;
    var eolState = cm.getStateAfter(pos.line);
    var inList = eolState.list !== false;

    if (inList) {
        cm.execCommand('indentMore');
        return;
    }

    if (cm.options.indentWithTabs) {
        cm.execCommand('insertTab');
    } else {
        var spaces = Array(cm.options.tabSize + 1).join(' ');
        cm.replaceSelection(spaces);
    }
};

CodeMirror.commands.shiftTabAndUnindentMarkdownList = function (cm) {
    var ranges = cm.listSelections();
    var pos = ranges[0].head;
    var eolState = cm.getStateAfter(pos.line);
    var inList = eolState.list !== false;

    if (inList) {
        cm.execCommand('indentLess');
        return;
    }

    if (cm.options.indentWithTabs) {
        cm.execCommand('insertTab');
    } else {
        var spaces = Array(cm.options.tabSize + 1).join(' ');
        cm.replaceSelection(spaces);
    }
};
