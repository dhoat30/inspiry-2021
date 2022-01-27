import './frontend.scss'
import React from 'react'
import ReactDOM from 'react-dom'

const divsToUpdate = document.querySelectorAll('.webduel-accordion-update-me')

divsToUpdate.forEach((div) => {
    ReactDOM.render(<Accordion />, div)
    div.classList.remove('webduel-accordion-update-me')
})

function Accordion() {
    return (
        <div className="accordion-frontend">Hello from react</div>

    )
}