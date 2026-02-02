import { mariageData } from "../data/data.js";

console.log("mariage.js chargé");

// 1️⃣ on lit l'URL
const params = new URLSearchParams(window.location.search);
const type = params.get("type") || "faire-part";

// 2️⃣ on récupère les bonnes données
const themes = mariageData[type];

// 3️⃣ sécurité si quelqu’un bidouille l’URL
if (!themes) {
  console.warn("Type inconnu :", type);
  return;
}

// 4️⃣ on génère le HTML
const container = document.querySelector(".row");

themes.forEach(theme => {
  container.innerHTML += `
    <div class="col-md-4 col-lg-3 p-0">
      <div class="portfolio-item text-center">
        <a href="https://impressthem.fr/modeles/mariage/${theme.slug}">
          <img src="${theme.image}" alt="${theme.title}" class="img-fluid">
          <h2>${theme.title}</h2>
        </a>
      </div>
    </div>
  `;
});