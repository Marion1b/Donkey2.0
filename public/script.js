import { Tickets } from "./Tickets.js";

document.addEventListener('DOMContentLoaded', function(){
    const buyTickets = document.querySelector(".buy-tickets");
    if(buyTickets){
        const select = document.querySelectorAll(".buy-tickets select");
        const par = document.querySelector(".tickets-total p");
        const hidePrice = document.querySelector("#tickets-total-input");
        const tickets = new Tickets(select);
        select.forEach((element) =>{
            element.addEventListener('change', (e) =>{
                par.innerText = tickets.total() + "€";
                hidePrice.value = tickets.total();
            })
        })
    }
})