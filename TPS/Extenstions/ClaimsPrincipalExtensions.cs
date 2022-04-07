using System.Linq;
using System.Security.Claims;

namespace TPS.Extensions
{
    public static class ClaimsPrincipalExtensions
    {
        private static string getClaimValue(ClaimsPrincipal claimsPrincipal, string claimType)
        {
            return claimsPrincipal.Claims.FirstOrDefault(c => c.Type == claimType)?.Value;
        }

        public static string GetUserId(this ClaimsPrincipal claimsPrincipal)
        {
            if (claimsPrincipal == null)
                return null;
            return getClaimValue(claimsPrincipal, ClaimTypes.NameIdentifier);
        }

        public static string GetName(this ClaimsPrincipal claimsPrincipal)
        {
            if (claimsPrincipal == null)
                return null;
            return getClaimValue(claimsPrincipal, "name");
        }

        public static string GetEmail(this ClaimsPrincipal claimsPrincipal)
        {
            if (claimsPrincipal == null)
                return null;
            return getClaimValue(claimsPrincipal, ClaimTypes.Email);
        }

        public static string GetObjectId(this ClaimsPrincipal claimsPrincipal)
        {
            if (claimsPrincipal == null)
                return null;
            return getClaimValue(claimsPrincipal, "http://schemas.microsoft.com/identity/claims/objectidentifier");
        }
    }
}
