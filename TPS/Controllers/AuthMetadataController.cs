using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;

namespace TPS.Controllers;
[Route("api/[controller]")]
[ApiController]
public class AuthMetadataController : ControllerBase
{
    public class AuthMetadata
    {
        public AuthMetadata(string clientId)
        {
            ClientId = clientId;
        }
        public string ClientId { get; set; }
    }

    private readonly IConfiguration _configuration;
    public AuthMetadataController(IConfiguration configuration)
    {
        _configuration = configuration;
    }

    [HttpGet]
    public AuthMetadata Index() => new AuthMetadata(_configuration["ClientId"]);

    [Authorize]
    [ProducesResponseType(StatusCodes.Status200OK)]
    [HttpGet("Authed")]
    public bool IsAuthed() => true;
}
