const input = document.querySelectorAll('.custom-file-input')
const button = document.querySelectorAll('.modify')
const removeButton = document.querySelectorAll('.remove')

input.forEach((element) => {
    element.classList.add('d-none')            
})

button.forEach(btn=> {
    btn.addEventListener('click', (e)=>{
        const thisInput= e.currentTarget.parentNode.parentNode.querySelector('input')                
        const image= e.currentTarget.parentNode.parentNode.querySelector('.previsu')

        thisInput.click()
        thisInput.addEventListener('change', (e) =>{                    
            image.src = URL.createObjectURL(e.currentTarget.files[0])
        } )
    }) 
}); 

removeButton.forEach(btn => {
    btn.addEventListener('click', async function(e){
        e.preventDefault();
        if(confirm("Voulez-vous supprimer cette image ?")){
            try {
                const response = await fetch(e.currentTarget.getAttribute("href"),{
                    method: "DELETE",
                    headers: {
                        "X-requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    }
                    
                })
                this.parentNode.parentNode.classList.add('d-none')
            } catch (err) {
                
            }
            
        }
            
    })
});


