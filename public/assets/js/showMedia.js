const element = document.getElementById('loadMedia');
element.addEventListener('click', (e)=>{
    e.preventDefault;
    const node = document.getElementById('mediaBlock');
    node.classList.remove('d-sm-none','d-none');
    element.classList.add('d-none');
    document.getElementById('hideMedia').classList.remove('d-none');
})

document.getElementById('hideMedia').addEventListener('click', (e)=>{
    e.preventDefault;
    document.getElementById('mediaBlock').classList.add('d-sm-none','d-none') ;
    document.getElementById('loadMedia').classList.remove('d-none')
    document.getElementById('hideMedia').classList.add('d-none')
})