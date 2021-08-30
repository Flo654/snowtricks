
    const newImage= (e)=>{
        
        const collectionHolder = document.querySelector(e.currentTarget.dataset.collection)
        const item = document.createElement('div');        
        item.classList.add('col-md-4');
        item.classList.add('mb-3');        
        item.innerHTML += collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g, 
            collectionHolder.dataset.index
        );
        item.querySelector('.btn-remove').addEventListener('click', ()=> item.remove());        
        collectionHolder.appendChild(item);
       
        collectionHolder.querySelectorAll('.custom-file').forEach(item => { 
            item.addEventListener('change', (e)=>{
                const img = document.createElement('img')
                img.classList.add('img-thumbnail')
                img.style.width = '200'           
                e.currentTarget.appendChild(img)        
                var url = URL.createObjectURL(e.currentTarget.querySelector('input').files[0]);
                img.src = url
                //e.currentTarget.querySelector('input').remove(e.currentTarget.querySelector('input').files[0].name)            
            })           
        })    
        collectionHolder.dataset.index ++;    
    }

    const newVideo = (e)=>{
        const collectionHolder = document.querySelector(e.currentTarget.dataset.collection)
        const item = document.createElement('div');        
        item.classList.add('col-md-4');
        item.classList.add('mb-3');        
        item.innerHTML += collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g, 
            collectionHolder.dataset.index
        );
        item.querySelector('.btn-remove').addEventListener('click', ()=> item.remove());        
        collectionHolder.appendChild(item);
        
        collectionHolder.querySelectorAll('input').forEach(element => {
            element.addEventListener('change', (e)=>{ 
                const path = e.currentTarget.value
                var video = path.substring(path.lastIndexOf('/') + 1);
                const iframe = e.currentTarget.previousSibling.parentNode.nextElementSibling.querySelector('iframe')
                iframe.src = "https://www.youtube.com/embed/" + video
                iframe.classList.remove('d-none')
                iframe.setAttribute("class", video)
            })
        })
        
        collectionHolder.dataset.index ++; 
    }

    document.querySelectorAll('.btn-remove')
    .forEach(btn => btn
    .addEventListener('click', (e)=> e.currentTarget.closest('.item').remove()));

    document.querySelector('.btn-new-image')  
    .addEventListener('click', newImage);

    document.querySelectorAll('.btn-new-video')
    .forEach(btn => btn
    .addEventListener('click', newVideo));