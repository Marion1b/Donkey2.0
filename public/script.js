import { Tickets } from "./Tickets.js";

document.addEventListener('DOMContentLoaded', function(){
    const buyTickets = document.querySelector(".buy-tickets");
    if(buyTickets){
        const select = document.querySelectorAll(".buy-tickets select");
        const par = document.querySelector(".tickets-total p");
        const hidePrice = document.querySelector("#tickets-total-input");
        const button = document.querySelector(".buy-tickets button");
        const lastName = document.querySelector("#last_name");
        const firstName = document.querySelector('#first_name');
        const email = document.querySelector('#email');
        const tickets = new Tickets(select);
        button.disabled = true;
        let ticketsTotal = false;

        select.forEach((element) =>{
            element.addEventListener('change', (e) =>{
                par.innerText = tickets.total() + "€";
                hidePrice.value = tickets.total();
                if(tickets.total()>1){
                    ticketsTotal = true;
                }
            })
        })

        buyTickets.addEventListener('input', ()=>{
            if(ticketsTotal === true && lastName.value && firstName.value && email.value){
                button.disabled = false;
            }
        })
    }
})