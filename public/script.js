import { Tickets } from "./Tickets.js";

document.addEventListener('DOMContentLoaded', function(){
    const buyTickets = document.querySelector(".buy-tickets");
    if(buyTickets){
        const select = document.querySelectorAll(".buy-tickets select");
        const tickets = new Tickets(select);
        select.forEach((element) =>{
            element.addEventListener('change', (e) =>{
                // console.log(element);
                console.log(tickets.total());
            })
        })
    }
})