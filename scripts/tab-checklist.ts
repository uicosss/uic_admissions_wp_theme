document.addEventListener("DOMContentLoaded", () => {
  const blocks = document.querySelectorAll<HTMLElement>(".tab-checklist");

  blocks.forEach((block) => {
    const tabs = block.querySelectorAll<HTMLButtonElement>(".tab-checklist__tab");
    const panels = block.querySelectorAll<HTMLElement>(".tab-checklist__panel");

    function activateTab(target: string) {
      tabs.forEach((tab) => {
        const isActive = tab.dataset.tabTarget === target;

        tab.classList.toggle("is-active", isActive);
        tab.setAttribute("aria-selected", isActive ? "true" : "false");
        tab.tabIndex = isActive ? 0 : -1;
      });

      panels.forEach((panel) => {
        const isActive = panel.dataset.tabPanel === target;

        panel.classList.toggle("is-active", isActive);
        if (isActive) panel.removeAttribute("hidden");
        else panel.setAttribute("hidden", "");
      });
    }

    tabs.forEach((tab) => {
      tab.addEventListener("click", () => {
        const target = tab.dataset.tabTarget;
        if (!target) return;
        activateTab(target);
      });

      tab.addEventListener("keydown", (e) => {
        if (e.key !== "ArrowLeft" && e.key !== "ArrowRight") return;
        e.preventDefault();

        const arr = Array.from(tabs);
        const idx = arr.indexOf(tab);
        let next = e.key === "ArrowRight" ? idx + 1 : idx - 1;
        if (next < 0) next = arr.length - 1;
        if (next >= arr.length) next = 0;

        arr[next].focus();
        arr[next].click();
      });
    });

    const accordBtns = block.querySelectorAll<HTMLButtonElement>(".tab-checklist__itembtn");

    accordBtns.forEach((btn) => {
      btn.addEventListener("click", () => {
        const expanded = btn.getAttribute("aria-expanded") === "true";
        const contentId = btn.getAttribute("aria-controls");
        if (!contentId) return;

        const content = block.querySelector<HTMLElement>(`#${CSS.escape(contentId)}`);
        btn.setAttribute("aria-expanded", expanded ? "false" : "true");

        if (content) {
          if (expanded) content.setAttribute("hidden", "");
          else content.removeAttribute("hidden");
        }
      });
    });
  });
});