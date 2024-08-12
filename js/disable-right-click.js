function disableRightClick(event) {
    event.preventDefault();
}

function disableTextSelection(event) {
    event.preventDefault();
}

function disableDrag(event) {
    event.preventDefault();
}

function disableKeyShortcuts(event) {
    if (event.ctrlKey || event.metaKey) {
        const blockedKeys = ['c', 'x', 's', 'p', 'u', 'v', 'a'];
        if (blockedKeys.includes(event.key.toLowerCase())) {
            event.preventDefault();
        }
    }
    if (event.key === 'F12' || (event.ctrlKey && event.shiftKey && event.key === 'I')) {
        event.preventDefault();
    }
}

function applyToIframes() {
    var iframes = document.getElementsByTagName('iframe');
    for (var i = 0; i < iframes.length; i++) {
        try {
            const iframeDocument = iframes[i].contentWindow.document;
            iframeDocument.addEventListener('contextmenu', disableRightClick);
            iframeDocument.addEventListener('selectstart', disableTextSelection);
            iframeDocument.addEventListener('dragstart', disableDrag);
            iframeDocument.addEventListener('keydown', disableKeyShortcuts);
        } catch (e) {
            console.error('No se pudo aplicar las restricciones en un iframe:', e);
        }
    }
}

function toggleSecurityScripts(enable) {
    if (enable) {
        document.addEventListener('contextmenu', disableRightClick);
        document.addEventListener('selectstart', disableTextSelection);
        document.addEventListener('dragstart', disableDrag);
        document.addEventListener('keydown', disableKeyShortcuts);

        var images = document.getElementsByTagName('img');
        for (var i = 0; i < images.length; i++) {
            images[i].setAttribute('draggable', false);
        }

        applyToIframes();
    } else {
        document.removeEventListener('contextmenu', disableRightClick);
        document.removeEventListener('selectstart', disableTextSelection);
        document.removeEventListener('dragstart', disableDrag);
        document.removeEventListener('keydown', disableKeyShortcuts);

        var images = document.getElementsByTagName('img');
        for (var i = 0; i < images.length; i++) {
            images[i].removeAttribute('draggable');
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if (typeof is_admin === 'undefined' || !is_admin) {
        var disableRightClickCheckbox = document.getElementById('paretomed_disable_right_click');
        if (disableRightClickCheckbox.checked) {
            toggleSecurityScripts(true);
        }
        disableRightClickCheckbox.addEventListener('change', function() {
            toggleSecurityScripts(this.checked);
        });
    }
});
