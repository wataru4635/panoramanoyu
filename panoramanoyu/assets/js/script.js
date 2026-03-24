"use strict";

/* ===============================================
# スクロールアニメーション
=============================================== */
document.addEventListener("DOMContentLoaded", () => {
  const COMMON_TRIGGER_RATIO = 0.85;
  function observeElements(selector, activeClass = "is-active", options = {}, keepActive = false) {
    const elements = document.querySelectorAll(selector);
    if (!elements.length) return;
    const triggerPoint = window.innerHeight * COMMON_TRIGGER_RATIO;
    const observer = new IntersectionObserver(
      (entries, observer2) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add(activeClass);
            if (!keepActive) {
              observer2.unobserve(entry.target);
            }
          } else if (!keepActive) {
            entry.target.classList.remove(activeClass);
          }
        });
      },
      options
    );
    elements.forEach((el) => {
      const rect = el.getBoundingClientRect();
      if (rect.top < triggerPoint) {
        el.classList.add(activeClass);
      } else {
        observer.observe(el);
      }
    });
  }
  function getRootMargin(pcMargin, spMargin) {
    return window.matchMedia("(min-width: 768px)").matches ? pcMargin : spMargin;
  }
  observeElements(".js-fade-in", "is-active", {
    rootMargin: getRootMargin("0px 0px -10% 0px", "0px 0px -10% 0px")
  });
  observeElements(".js-fade-up", "is-active", {
    rootMargin: getRootMargin("0px 0px -20% 0px", "0px 0px -10% 0px")
  });
  observeElements(".js-scaleImg", "is-active", {
    rootMargin: getRootMargin("0px 0px -20% 0px", "0px 0px -5% 0px")
  });
});
/* ===============================================
# 文字を1文字ずつ <span> に分割
=============================================== */
function wrapTextInSpans(selector) {
  document.querySelectorAll(selector).forEach((element) => {
    const text = element.textContent;
    element.setAttribute("aria-label", text);
    element.setAttribute("role", "text");
    element.textContent = "";
    [...text].forEach((char, index) => {
      const span = document.createElement("span");
      span.textContent = char;
      span.style.setProperty("--index", index);
      span.setAttribute("aria-hidden", "true");
      element.appendChild(span);
    });
  });
}
wrapTextInSpans(".js-text-split");
