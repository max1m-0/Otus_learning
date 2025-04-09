BX.ready(function () {
    BX.addCustomEvent('onTimeManWindowOpen', function (timeman) {
        console.log('Открылось окно тайммена');

        const interval = setInterval(() => {
            const startBtn = document.querySelector('.ui-btn.ui-btn-success.ui-btn-icon-start');
            if (!startBtn) return;

            clearInterval(interval);

            const clonedBtn = startBtn.cloneNode(true);
            startBtn.parentNode.replaceChild(clonedBtn, startBtn);

            clonedBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const popupContent = BX.create("div", {
                    children: [
                        BX.create("div", {
                            text: "Вы уверены, что хотите начать рабочий день?",
                            style: { marginBottom: "10px" }
                        }),
                        BX.create("button", {
                            text: "Да, начать рабочий день",
                            attrs: { className: "ui-btn ui-btn-primary" },
                            events: {
                                click: function () {
                                    popup.close();
                                        startBtn.click();
                                }
                            }
                        })
                    ]
                });

                const popup = BX.PopupWindowManager.create("timeman-confirm-start", null, {
                    content: popupContent,
                    closeIcon: { right: "10px", top: "10px" },
                    autoHide: true,
                    overlay: true,
                    draggable: true,
                    titleBar: "Подтверждение начала рабочего дня",
                    events: {
                        onClose: function () {
                            if (timeman && timeman.POPUP) {
                                timeman.POPUP.close();
                            }
                        }
                    }
                });

                popup.show();
            });
        }, 300);
    });
});
