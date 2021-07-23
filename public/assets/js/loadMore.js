const element =  document.getElementById('loadMore');
const limitPage = parseInt(document.getElementById('page').getAttribute('name'));
element.addEventListener('click', (e)=>{
    e.preventDefault();
    axios.get(element.getAttribute('href')).then((response)=>{

        let page = parseInt(element.getAttribute('name'))+1
        let path = '/tricks/' + page
        if (page > limitPage){
            element.classList.add('d-none')
        }        
        document.getElementById('trick').insertAdjacentHTML('beforeEnd', response.data);
        element.setAttribute('href', path );
        element.setAttribute('name', (parseInt(element.getAttribute('name'))+1) )
    })
})