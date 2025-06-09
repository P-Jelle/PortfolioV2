// document.addEventListener("DOMContentLoaded", () => {
//     const sections = document.querySelectorAll(".toggle-section");

//     sections.forEach((section) => {
//         section.addEventListener("click", () => {
//             const content = section.querySelector(".hidden-content");
//             const isExpanded = content.style.height !== "0px";

//             section
//                 .querySelector("h2")
//                 .setAttribute("aria-expanded", !isExpanded);
//             content.setAttribute("aria-hidden", isExpanded);

//             sections.forEach((otherSection) => {
//                 if (otherSection !== section) {
//                     const otherContent =
//                         otherSection.querySelector(".hidden-content");
//                     otherContent.style.height = "0";
//                     otherSection
//                         .querySelector("h2")
//                         .setAttribute("aria-expanded", "false");
//                     otherContent.setAttribute("aria-hidden", "true");
//                 }
//             });

//             content.style.height = isExpanded
//                 ? "0"
//                 : `${content.scrollHeight}px`;
//         });
//     });

//     const hiddenContents = document.querySelectorAll(".hidden-content");
//     hiddenContents.forEach((content) => {
//         content.style.height = "0";
//         content.style.overflow = "hidden";
//         content.style.transition = "height 0.5s ease-out";
//     });

//     const links = document.querySelectorAll("a");
//     links.forEach((link) => {
//         link.addEventListener("click", (event) => {
//             event.stopPropagation();
//         });
//     });
// });
