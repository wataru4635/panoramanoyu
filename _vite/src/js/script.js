import "../sass/style.scss";

/* ===============================================
# スクロールアニメーション
=============================================== */
document.addEventListener("DOMContentLoaded", () => {
  const TRIGGER_RATIO = 0.85;
  const refreshFns = [];

  function observeElements(selector, activeClass = "is-active", options = {}, keepActive = false) {
    const elements = document.querySelectorAll(selector);
    if (!elements.length) return;

    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add(activeClass);
          if (!keepActive) obs.unobserve(entry.target);
        } else if (keepActive) {
          entry.target.classList.remove(activeClass);
        }
      });
    }, options);

    const refresh = () => {
      const triggerPoint = window.innerHeight * TRIGGER_RATIO;

      elements.forEach((el) => {
        if (el.classList.contains(activeClass)) return;
        if (el.getBoundingClientRect().top < triggerPoint) {
          el.classList.add(activeClass);
          if (!keepActive) observer.unobserve(el);
        }
      });
    };

    elements.forEach((el) => observer.observe(el));
    refresh();
    refreshFns.push(refresh);
  }

  const getRootMargin = (pc, sp) =>
    window.matchMedia("(min-width: 768px)").matches ? pc : sp;

  const refreshAll = () => refreshFns.forEach((fn) => fn());

  const debounce = (fn, delay = 200) => {
    let timer;
    return () => {
      clearTimeout(timer);
      timer = setTimeout(fn, delay);
    };
  };

  const recover = () => {
    refreshAll();
    requestAnimationFrame(() => {
      requestAnimationFrame(refreshAll);
    });
    setTimeout(refreshAll, 300);
    setTimeout(refreshAll, 1000);
  };

  observeElements(".js-fade-in", "is-active", {
    rootMargin: getRootMargin("0px 0px -10% 0px", "0px 0px -10% 0px")
  });

  observeElements(".js-fade-up", "is-active", {
    rootMargin: getRootMargin("0px 0px -20% 0px", "0px 0px -10% 0px")
  });

  observeElements(".js-scaleImg", "is-active", {
    rootMargin: getRootMargin("0px 0px -20% 0px", "0px 0px -5% 0px")
  });

  window.addEventListener("load", recover, { once: true });
  window.addEventListener("pageshow", refreshAll);
  window.addEventListener("resize", debounce(refreshAll, 200));

  let restored = false;
  const onScrollRestore = () => {
    if (restored) return;
    restored = true;
    refreshAll();
    window.removeEventListener("scroll", onScrollRestore);
  };

  window.addEventListener("scroll", onScrollRestore, { passive: true });

  window.addEventListener("load", () => {
    setTimeout(() => {
      restored = true;
      window.removeEventListener("scroll", onScrollRestore);
    }, 3000);
  }, { once: true });
});

/* ===============================================
# 文字を1文字ずつ <span> に分割
=============================================== */
function wrapTextInSpans(selector) {
  document.querySelectorAll(selector).forEach(element => {
    const text = element.textContent;
    element.setAttribute('aria-label', text);
    element.setAttribute('role', 'text');
    element.textContent = '';
    [...text].forEach((char, index) => {
      const span = document.createElement('span');
      span.textContent = char;
      span.style.setProperty('--index', index);
      span.setAttribute('aria-hidden', 'true');
      element.appendChild(span);
    });
  });
}
wrapTextInSpans(".js-text-split");

