const elementbutton =  document.getElementById('loadMore');

const limitPage = parseInt(elementbutton.dataset.page);
const trickId = parseInt(elementbutton.dataset.id);
console.log(limitPage, trickId)
elementbutton.addEventListener('click', (e)=>{
    e.preventDefault();
        axios.get(elementbutton.getAttribute('href')).then((response)=>{
        let pages = parseInt(elementbutton.dataset.page)+1
        let path = trickId + '/comments/' + pages
        if (pages > limitPage){
            elementbutton.classList.add('d-none')
        }        
        document.getElementById('comment').insertAdjacentHTML('beforeEnd', response.data);
        elementbutton.setAttribute('href', path );        
        elementbutton.dataset.page = pages 
    })
})

