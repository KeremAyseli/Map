using MapApi.Models;
using MapApi.Services;
using Microsoft.AspNetCore.Mvc;
using System.Collections.Generic;
namespace MapApi.Controllers
{
    [Route("/spiral")]
    [ApiController]

    public class SpiralController : ControllerBase
    {
        private readonly SpiralServices _spiralServices;
        public SpiralController(SpiralServices spiral)
        {
            _spiralServices = spiral;
        }
        [HttpPost()]
        public List<MatrisDTO> Matris(int x,int y){
            
            return  _spiralServices.GetLayouts();
        }
    }
}