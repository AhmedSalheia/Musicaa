let arr = document.querySelector('#sidebar-toggle');

arr.onclick = function ()
{
    let close="ti-close";

    if (arr.firstChild.classList.contains(close))
    {
        arr.firstChild.classList.remove(close);
    }else
    {
        arr.firstChild.classList.add(close);
    }
}
