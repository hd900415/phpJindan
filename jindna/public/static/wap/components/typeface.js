var rem = {
    baseRem: 20,
    baseWidth: 375,
    rootEle: document.getElementsByTagName('html')[0],
    initHandle: function() {
        this.setRemHandle();
        this.resizeHandle();
    },
    setRemHandle: function() {
        var clientWidth = document.documentElement.clientWidth;
        this.rootEle.style.fontSize = clientWidth * this.baseRem / this.baseWidth + "px";
    },
    resizeHandle: function() {
        var that = this;
        window.addEventListener("resize",
        function() {
            setTimeout(function() {
                that.setRemHandle();
            },
            300);
        });
    }
};
rem.initHandle();
