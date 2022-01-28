function requireAll(r) {
    r.keys().forEach(r);
}

requireAll(require.context('@icons', true, /\.svg$/));

const host = window.location.origin;
let containerSvg = document.querySelector('#svg-icons')

if (site.themeAssetsUri){
    fetch(`${site.themeAssetsUri}/sprite.svg`)
        .then(res => {
            return res.text();
        })
        .then(data => {
            if (containerSvg) {
                containerSvg.innerHTML = data;
            } else {
                const containerSvg = document.createElement("div")

                containerSvg.id = 'svg-icons'

                document.body.insertAdjacentElement("beforeend", containerSvg)

                containerSvg.innerHTML = data;
            }
        })
        .then(
            () => {
                const svg = document.querySelectorAll('.icon_svg')
            }
        );
}else{
    const svg = document.querySelectorAll('.icon_svg')

    svg.forEach((item)=>{
        item.style.display = 'none';
    })
}
