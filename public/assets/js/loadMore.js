const element =  document.getElementById('loadMore');
element.addEventListener('click', (e)=>{
    e.preventDefault();
    axios.get(element.getAttribute('href')).then((response)=>{

        page = '/tricks/' + (parseInt(element.getAttribute('name'))+1)
        document.getElementById('trick').insertAdjacentHTML('beforeEnd', response.data);
        element.setAttribute('href', page );
        element.setAttribute('name', (parseInt(element.getAttribute('name'))+1) )
    })
})